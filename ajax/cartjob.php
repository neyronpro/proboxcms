<?php session_start();

  if(!empty($_POST['addcart'])){
	$product_id = (int)$_POST['idcart'];
	if(!empty($_POST['count'])){
		$count = (int)$_POST['count'];
	} else {
		$count = "1";
	}
	$cost = (int)$_POST['discost'];
	if (!empty($_SESSION['products'][$product_id])) {
	//увеличиваем количество на единицу, если товар уже добавлен:
		$_SESSION['products'][$product_id]['count']+=$count;
	}
	else {
		//создаем пустой массив, на всякий случай, можно и без него.
		$_SESSION['products'][$product_id]=array();
		
		$_SESSION['products'][$product_id]['cost']=$cost;
		$_SESSION['products'][$product_id]['count']=$count;
	}
  }
	
	if(!empty($_POST['delgoods'])){
	$product_id = (int)$_POST['goodid'];
	unset($_SESSION['products'][$product_id]);

	}
	if(!empty($_SESSION['products'])){
		$_SESSION['cart_cost']=0;
		$_SESSION['cart_count']=0;
		foreach ($_SESSION['products'] as $key=>$value) {
			$_SESSION['cart_cost']+=$_SESSION['products'][$key]['cost']* $_SESSION['products'][$key]['count'];
			$_SESSION['cart_count']+=$_SESSION['products'][$key]['count'];
		}  
		}
	else{
		$_SESSION['cart_cost']=0;
		$_SESSION['cart_count']=0;
	}
	$minicartjson = array('total' => $_SESSION['cart_count'], 'sum' => $_SESSION['cart_cost']);
	echo json_encode($minicartjson);
?>

