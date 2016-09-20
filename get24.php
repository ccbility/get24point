<?php
	/**
	*本程序是：24点游戏，随机生成1~13之间的4个数字，求24
	*/
	error_reporting(0);

	//生成随机的四个数
	for($i = 0; $i < 4; $i++){
		$nums[] = rand(1, 13);
	}
	$ops = ['+', '-', '*', '/'];

	echo '<pre />';
	print_r($nums);

	$result = get24($nums, $ops);

	//结果输出
	if($result){
		echo 'Answer:<br /><br />';
		
		foreach ($result as $value) {
			echo $value, '<br />';
		}
	}else{
		echo 'No Answer!';
	}
	function get24($nums, $ops){
		$result = [];

		for ($i = 0; $i < 4; $i++) { 
			$a = $nums[$i];
			for($j = 0; $j < 4; $j++){
				if($j !== $i){
					$b = $nums[$j];
					for($x = 0; $x < 4; $x++){
						if($x !== $i && $x !== $j){
							$c = $nums[$x];
							for($y = 0; $y < 4; $y++){
								if($y !== $i && $y !== $j && $y !== $x){
									$d = $nums[$y];

									//$a $b $c $d 几个数的顺序确定了再拼接上运算符
									for ($z1 = 0; $z1 < 4; $z1++) { 
										for($z2 = 0; $z2 < 4; $z2++){
											for($z3 = 0; $z3 < 4; $z3++){
												//介于下面多次用到操作符，干脆提取出来，提高速度
												$ops1 = $ops[$z1];
												$ops2 = $ops[$z2];
												$ops3 = $ops[$z3];

												//模式一，先计算前两位或者前三位

												$evalStr = $a . $ops1 . $b . $ops2 . $c . $ops3 . $d;
												$part1 = $a. $ops1 . $b;
												$part1Result = eval("return $part1;");
												$part2Result = eval("return $part1Result $ops2 $c;");
												$part3Result = eval("return $part2Result $ops3 $d;");

												if($part3Result == 24){
													$bool = 1;

													if(($ops1 == '-' || $ops1 == '+') && ($ops2 == '/' || $ops2 == '*')){
														$evalStr = '(' . $part1 . ')' . $ops2 . $c . $ops3 . $d;
													}
													if(($ops2 == '-' || $ops2 == '+') && ($ops3 == '/' || $ops3 == '*')){
														$evalStr = '(' . $part1 . $ops2 . $c . ')' . $ops3 . $d;
													}

													$result[] = $evalStr;
												}


												//模式二，先计算两边
												$part2Result = eval("return $c $ops3 $d;");
												$part3Result = eval("return $part1Result $ops2 $part2Result;");
												if($part3Result == 24){
													$bool = 1;
													//加括号规则，第二个括号只有在第二个运算符是+时不加，其他时候都加上
													//当然第二个运算符为*，第三个也为*时，也可以不加，但还是加上吧，
													//作为一个标识是先运算后面，而不是顺序运算
													if(($ops1 == '-' || $ops1 == '+') && ($ops2 == '/' || $ops2 == '*')){
														$evalStr = '(' . $part1 . ')' . $ops2 . '(' . $c . $ops3 . $d . ')';
													}
													if(($ops1 == '*' || $ops1 == '/') && ($ops2 == '/' || $ops2 == '*')){
														$evalStr = $part1 . $ops2 . '(' . $c . $ops3 . $d . ')';
													}
													if(($ops3 == '-' || $ops3 == '+') && $ops2 == '-'){
														$evalStr = $part1 . $ops2 . '(' . $c . $ops3 . $d . ')';
													}
													$result[] = $evalStr;
												}

											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return array_unique($result);
	}