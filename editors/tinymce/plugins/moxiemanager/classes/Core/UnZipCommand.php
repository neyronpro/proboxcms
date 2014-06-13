<?php
/**
 * UnZip.php
 *
 * Copyright 2003-2013, Moxiecode Systems AB, All rights reserved.
 */

/**
 * Command for unzipping zip files on a remote file system.
 *
 * @package MOXMAN_Core
 */
class MOXMAN_Core_UnZipCommand extends MOXMAN_Core_BaseCommand {
	/**
	 * Executes the command logic with the specified RPC parameters.
	 *
	 * @param Object $params Command parameters sent from client.
	 * @return Object Result object to be passed back to client.
	 */
	public function execute($params) {
		$fromFile = MOXMAN::getFile($params->from);
		$toFile = MOXMAN::getFile($params->to);
		$config = $toFile->getConfig();

		if ($config->get('general.demo')) {
			throw new MOXMAN_Exception(
				"This action is restricted in demo mode.",
				MOXMAN_Exception::DEMO_MODE
			);
		}

		if (!$fromFile->exists()) {
			throw new MOXMAN_Exception(
				"From file doesn't exist: " . $fromFile->getPublicPath(),
				MOXMAN_Exception::FILE_DOESNT_EXIST
			);
		}

		if (!$toFile->canWrite()) {
			throw new MOXMAN_Exception(
				"No write access to file: " . $toFile->getPublicPath(),
				MOXMAN_Exception::NO_WRITE_ACCESS
			);
		}

		$paths = array();
		$fileSystemManager = MOXMAN::getFileSystemManager();
		$zipArchive = new ZipArchive();
		$localTempFilePath = null;
		$result = array();

		if ($fromFile instanceof MOXMAN_Vfs_Local_File) {
			$res = $zipArchive->open($fromFile->getPath());
		} else {
			$localTempFilePath = $fileSystemManager->getLocalTempPath($fromFile);
			$fromFile->exportTo($localTempFilePath);
			$res = $zipArchive->open($localTempFilePath);
		}

		if ($res) {
			for ($i = 0; $i < $zipArchive->numFiles; $i++) {
				$stat = $zipArchive->statIndex($i);
				$paths[] = $stat["name"];
			}

			$filter = MOXMAN_Vfs_BasicFileFilter::createFromConfig($config);
			$fileSystem = $toFile->getFileSystem();

			foreach ($paths as $path) {
				$isFile = !preg_match('/\/$/', $path);
				$toPath = MOXMAN_Util_PathUtils::combine($toFile->getPath(), iconv('cp437', 'UTF-8', $path));
				$targetFile = MOXMAN::getFile($toPath);

				if ($filter->accept($targetFile, $isFile) === MOXMAN_Vfs_IFileFilter::ACCEPTED) {
					if ($isFile) {
						$content = $zipArchive->getFromName($path);

						// Fire before file action add event
						$args = new MOXMAN_Core_FileActionEventArgs("add", $targetFile);
						$args->getData()->fileSize = strlen($content);
						MOXMAN::getPluginManager()->get("core")->fire("BeforeFileAction", $args);
						$targetFile = $args->getFile();

						$targetFile = $this->mkdirs($targetFile, true);

						$stream = $targetFile->open(MOXMAN_Vfs_IFileStream::WRITE);
						$stream->write($content);
						$stream->close();
						//echo "Create file: ". $targetFile->getPublicPath() ."\n";

						$this->fireFileAction(MOXMAN_Core_FileActionEventArgs::ADD, $targetFile);
					} else {
						$targetFile = $this->mkdirs($targetFile);
					}

					$result[] = $this->fileToJson($targetFile);
				}
			}

			$zipArchive->close();

			if ($localTempFilePath) {
				$fileSystemManager->removeLocalTempFile($fromFile);
			}
		}

		return $result;
	}

	/** @ignore */
	private function mkdirs(MOXMAN_Vfs_IFile $file, $isFile=false) {
		$parents = array();

		$orgFile = $file;

		if ($isFile) {
			$file = $file->getParentFile();
		}

		$pathChunks = explode("/", $file->getPublicPath());

		// Ignore first slash
		array_shift($pathChunks);
		$path = "";
		$chunkFile = null;

		foreach($pathChunks as $chunk) {
			$path .= "/". $chunk;
			$chunkFile = MOXMAN::getFile($path);

			// Ignore root
			if (!$chunkFile->getParent()) {
				continue;
			}

			$args = new MOXMAN_Core_FileActionEventArgs("add", $chunkFile);
			MOXMAN::getPluginManager()->get("core")->fire("BeforeFileAction", $args);
			$chunkFile = $args->getFile();
			$path = $chunkFile->getPublicPath();

			if (!$chunkFile->exists()) {
				$chunkFile->mkdir();
				$this->fireFileAction(MOXMAN_Core_FileActionEventArgs::ADD, $chunkFile);
			}
		}

		if ($chunkFile) {
			if ($isFile) {
				return MOXMAN::getFile($chunkFile->getPath(), $orgFile->getName());
			}

			return $chunkFile;
		}

		return $orgFile;
	}
}

?>