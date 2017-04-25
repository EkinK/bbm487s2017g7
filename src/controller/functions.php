<?php

session_start();
include_once 'db_connect.php';

echo '<link rel="stylesheet" href="css/style.css">';

function getPublisherId($p_name) {
	
	if($p_name == NULL) { /* control param - just in case */
		return -1;
	}
	
	/* get publisher record from database */
	$sql = mysqli_query($_SESSION["conn"], "select publisherID from publishers where p_name = '".$p_name."'");
	
	if(mysqli_num_rows($sql))  { /* if a record exist */
		//printf("return id is %d", mysqli_fetch_object($sql)->publisherID);
		return mysqli_fetch_object($sql)->publisherID; /* return id of publisher */
	}
	else { /* there is no such publisher. create new one. */
		
		mysqli_query($_SESSION["conn"], "insert into publishers(p_name) values('".$p_name."')");
		$record_id = mysqli_insert_id($_SESSION["conn"]);	/* get the id of new publisher */
		return $record_id; /* 0 if insertion fails */
	}
}

function getAuthorId($a_name, $a_surname) {
	
	if(($a_name == NULL) || ($a_surname == NULL)) { /* control params - just in case */
		return -1;
	}
	
	/* get author record from database */
	$query = "select authorID from authors where a_name = '".$a_name."' and a_surname = '".$a_surname."' ";
	$sql = mysqli_query($_SESSION["conn"], $query);
	
	if(mysqli_num_rows($sql))  { /* if a record exist */
		//printf("return id is %d", mysqli_fetch_object($sql)->authorID);
		return mysqli_fetch_object($sql)->authorID; /* return id of author */
	}
	else { /* there is no such author. create new one. */
		
		mysqli_query($_SESSION["conn"], "insert into authors(a_name, a_surname) values('".$a_name."', '".$a_surname."')");
		$record_id = mysqli_insert_id($_SESSION["conn"]);	/* get the id of new author */
		return $record_id; /* 0 if insertion fails */
	}
}

function addBook($title, $quantity, $a_name, $a_surname, $publisher_name) { /* creates a new book record */
	
	if(($title == NULL) || ($a_name == NULL) || ($a_surname == NULL) || ($publisher_name == NULL)) { /* control params - just in case */
		return -1;
	}
	$p_id = -1;
	$a_id = -1;
	$p_id = getPublisherId($publisher_name);
	if($p_id < 1) { /* failed getting id of publisher */
		return -1;
	}
	
	$a_id = getAuthorId($a_name, $a_surname);
	if($a_id < 1) { /* failed getting id of author */
		return -1;
	}
	
	$sql = mysqli_query($_SESSION["conn"], "select bookID, stock_num 
											from books 
											where title = '".$title."' 
											and authorID = '".$a_id."' 
											and publisherID = '".$p_id."' ");
	if(mysqli_num_rows($sql))  { /* if a record already exist */
	
		$result = mysqli_fetch_object($sql);
		$b_id = $result->bookID; /* return id of book */
		$stock_num = $result->stock_num; /* return stock number of book */
		
		if(($stock_num == NULL) || ($b_id == NULL)) {	
			/* something is wrong */
			return -1;
		}
		/* increase stock number of related book */
		$new_quantity = $quantity + $stock_num;
		$update = mysqli_query($_SESSION["conn"], "UPDATE books SET stock_num = ".$new_quantity." where bookID = ".$b_id."");
		
		return 1;
	}
	else {	/* record does not exist */
		/* create new book record */
		$insert = mysqli_query($_SESSION["conn"], "insert into books(title, stock_num, authorID, publisherID) values('".$title."', ".$quantity.", ".$a_id.", ".$p_id.")");
		
		if($insert == NULL)  { /* insertion failed */
			return -1;
		}
		else { /* insertion success */
			return 1;
		}
	}
}

function addUser($username, $pass, $name, $surname, $authority) {
	
	/* control params - just in case */
	if(($username == NULL) || ($pass == NULL) || ($name == NULL) || ($surname == NULL)) { 
		return -1;
	}
	
	$sql = mysqli_query($_SESSION["conn"], "select userID 
											from users
											where username = '".$username."'");
	
	if(mysqli_num_rows($sql))  { /* if a record already exist */
		
		return -2;
	}
	else {	/* record does not exist */
		/* create new book record */
		$query = "insert into users(username, password, name, surname, authority) 
							values('".$username."', '".$pass."', '".$name."', '".$surname."', ".$authority.")";
		$insert = mysqli_query($_SESSION["conn"], $query);
		
		if($insert == NULL)  { /* insertion failed */
			return -1;
		}
		else { /* insertion success */
			return 1;
		}
	}
}

function getBooks($str) {
	/* returns records has str in its' title or authors' name/surname */
	$query = "select b.bookID, b.title, b.stock_num, a.a_name, a.a_surname, p.p_name
			from books b 
				inner join authors a on b.authorID = a.authorID
				inner join publishers p on p.publisherID = b.publisherID
			where title like '%".$str."%' or a.a_name like '%".$str."%' or a.a_surname like '%".$str."%' ";
			
	$sql = mysqli_query($_SESSION["conn"], $query);
	
	return $sql;
}

function listUsers($authority) {
	/* returns records has same authority as parameter */
	$query = "select *
			from users 
			where authority = ".$authority." ";
			
	$users = mysqli_query($_SESSION["conn"], $query);
	
	if(mysqli_num_rows($users)) {	/* any records exist? */
		/* start printing table */
		echo '<tr>
				<th>Username</th>
				<th>Password</th>
				<th>Name</th>
				<th>Surname</th>
				<th></th>
			</tr>
		   <tbody>';
		while($row = mysqli_fetch_array($users)) {	/* for every user */
			/* show user properties */
			echo '<tr>
					<td>'.$row["username"].'</td>
					<td>'.$row["password"].'</td>
					<td>'.$row["name"].'</td>
					<td>'.$row["surname"].'</td>
					<td>
						<form action="update_user_page.php" method="POST">
							<input type="hidden" id="username" name="username" value="'.$row['username'].'">
							<input type="hidden" id="password" name="password" value="'.$row['password'].'">
							<input type="hidden" id="name" name="name" value="'.$row["name"].'">
							<input type="hidden" id="surname" name="surname" value="'.$row["surname"].'">
							<input type="hidden" id="userID" name="userID" value="'.$row['userID'].'">
							<button class="modify_btn" type="submit" style="letter-spacing: 0px;">UPDATE</button>
						</form>
					</td>
					<td>
						<form action="delete_user_confirm.php" method="POST">
							<input type="hidden" id="username" name="username" value="'.$row['username'].'">
							<input type="hidden" id="password" name="password" value="'.$row['password'].'">
							<input type="hidden" id="name" name="name" value="'.$row["name"].'">
							<input type="hidden" id="surname" name="surname" value="'.$row["surname"].'">
							<input type="hidden" id="userID" name="userID" value="'.$row['userID'].'">
							<button class="modify_btn" type="submit" style="letter-spacing: 0px;">DELETE</button>
						</form>
					</td>
			   </tr>';
		}
		echo '</tbody>';
	}else {	/* no record found */
		/* inform admin */
		echo'<tr><td style="color: #888; padding-top: 15px; padding: 10px; font-size: 14px; color: red;">No user has found!</td></tr>';
	}
}

/* lists book records includes given string in its' title or authors' name/surname */
function listBooks($str) { 
	$books = getBooks($str);
	
	if(mysqli_num_rows($books)) {	/* any records exist? */
		/* start printing table */
		echo '<tr>
				<th>Title</th>
				<th>Author</th>
				<th>Publisher</th>
				<th>Quantity</th>
				<th></th>
			</tr>
		   <tbody>';
		while($row = mysqli_fetch_array($books)) {	/* for every book */
			/* show book properties */
			echo '<tr>
					<td>'.$row["title"].'</td>
					<td>'.$row["a_name"].' '.$row["a_surname"].'</td>
					<td>'.$row["p_name"].'</td>
					<td>'.$row["stock_num"].'</td>
					<td>
						<form action="update_book_page.php" method="POST">
							<input type="hidden" id="bookID" name="bookID" value="'.$row['bookID'].'">
							<input type="hidden" id="bookID" name="title" value="'.$row['title'].'">
							<input type="hidden" id="bookID" name="a_name" value="'.$row["a_name"].'">
							<input type="hidden" id="bookID" name="a_surname" value="'.$row["a_surname"].'">
							<input type="hidden" id="bookID" name="publisher" value="'.$row['p_name'].'">
							<input type="hidden" id="bookID" name="quantity" value="'.$row['stock_num'].'">
							<button class="modify_btn" type="submit" style="letter-spacing: 0px;">UPDATE</button>
						</form>
					</td>
					<td>
						<form action="delete_book_confirm.php" method="POST">
							<input type="hidden" id="bookID" name="bookID" value="'.$row['bookID'].'">
							<input type="hidden" id="bookID" name="title" value="'.$row['title'].'">
							<input type="hidden" id="bookID" name="author" value="'.$row["a_name"].' '.$row["a_surname"].'">
							<input type="hidden" id="bookID" name="publisher" value="'.$row['p_name'].'">
							<input type="hidden" id="bookID" name="quantity" value="'.$row['stock_num'].'">
							<button class="modify_btn" type="submit" style="letter-spacing: 0px;">DELETE</button>
						</form>
					</td>
			   </tr>';
		}
		echo '</tbody>';
	}else {	/* no record found */
		/* inform admin */
		echo'<tr><td style="padding-top: 15px; padding: 10px; font-size: 14px; color: red;">No book has found!</td></tr>';
	}
}

function deleteSelectedBook($id) {
	
	$query = "delete from books where bookID = ".$id."";
	
	if(mysqli_query($_SESSION["conn"], $query)) {
		//success
		return 1;
	}else {
		// failed
		return -1;
	}
}

function updateSelectedBook($bookID, 
							$old_title, $title, 
							$old_a_name, $a_name, 
							$old_a_surname, $a_surname, 
							$old_p_name, $p_name, 
							$old_quantity, $quantity) {

	/* get id of old author */
	$a_id = getAuthorId($old_a_name, $old_a_surname);
	/* get id of old publisher */
	$p_id = getPublisherId($old_p_name);

	if((($old_a_name != $a_name) && ($a_name != NULL)) || (($old_a_surname != $a_surname) && ($a_surname != NULL))) {	/* author changed */
		
		if(($old_a_name != $a_name) && ($a_name != NULL)) {	/* author name is changed */
		
			if(($old_a_surname != $a_surname) && ($a_surname != NULL)) {	/* author surname is also changed */
				
				/* get id of new author */
				$a_id = getAuthorId($a_name, $a_surname);
			}
			else {
				
				/* get id of new author */
				$a_id = getAuthorId($a_name, $old_a_surname);
			}
		}
		else if(($old_a_surname != $a_surname) && ($a_surname != NULL)) {	/* author surname is changed */
			
			/* get id of new author */
			$a_id = getAuthorId($old_a_name, $a_surname);
		}
	} 
	if(($old_p_name != $p_name) && ($p_name != NULL)) {	/* author name is changed */
		
		/* get id of new author */
		$p_id = getPublisherId($p_name);
	}
	
	$new_quantity = $old_quantity;
	if($quantity != NULL) $new_quantity = $quantity;
	
	$new_title = $old_title;
	if($title) $new_title = $title;
	$query = "update books
				set title='".$new_title."',
					stock_num=".$new_quantity.",
					authorID=".$a_id.",
					publisherID=".$p_id."
				where bookID = ".$bookID."";
				
	if(mysqli_query($_SESSION["conn"], $query)) {
		return 1;
	}
	else {
		return -1;
	}
}


function deleteSelectedUser($id) {
	
	$query = "delete from users where userID = ".$id."";
	
	if(mysqli_query($_SESSION["conn"], $query)) {
		//success
		return 1;
	}else {
		// failed
		return -1;
	}
}

function updateSelectedUser($userID, 
							$old_username, $username, 
							$old_password, $password, 
							$old_name, $name, 
							$old_surname, $surname) {


	$new_username = $username;
	$new_password = $password;
	$new_name = $name;
	$new_surname = $surname;
	
	if($username == NULL) $new_username = $old_username;
	if($password == NULL) $new_password = $old_password;
	if($name == NULL) $new_name = $old_name;
	if($surname == NULL) $new_surname = $old_surname;
	
	$query = "update users
				set username='".$new_username."',
					password='".$new_password."',
					name='".$new_name."',
					surname='".$new_surname."'
				where userID = ".$userID."";
				
	if(mysqli_query($_SESSION["conn"], $query)) {
		return 1;
	}
	else {
		return -1;
	}
}

/* print messages will be optimized later.. */

function printInsertMessage($mode) {/* prints a message for inserting book */
	$str = "SUCCESS";
	$message = "The book has been inserted successfuly";
	$color = "green";
	if($mode == 0) {
		$message = "Book inserting has been failed";
		$color = "red";
		$str = "FAILURE";
	}
	echo '<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated zoomIn" style="max-width: 300px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">'.$str.'</h2>
					</div>
					<br/><a style="color: #888; padding-top: 15px; padding: 10px; font-size: 14px; color: '.$color.'"> '.$message.' </a><br/><br/><br/>
					<a href="add_book_page.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}

function printDeleteMessage($mode) {/* prints a message for deleting book */
	$str = "SUCCESS";
	$message = "The book has been deleted successfuly";
	$color = "green";
	if($mode == 0) {
		$message = "Book deleting has been failed";
		$color = "red";
		$str = "FAILURE";
	}
	echo '<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated zoomIn" style="max-width: 300px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">'.$str.'</h2>
					</div>
					<br/><a style="color: #888; padding-top: 15px; padding: 10px; font-size: 14px; color: '.$color.'"> '.$message.' </a><br/><br/><br/>
					<a href="search_book.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}

function printUpdateMessage($mode) {/* prints a message for modifying book */
	$str = "SUCCESS";
	$message = "The book has been updated successfuly";
	$color = "green";
	if($mode == 0) {
		$message = "Book updating has been failed";
		$color = "red";
		$str = "FAILURE";
	}
	echo '<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated zoomIn" style="max-width: 300px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">'.$str.'</h2>
					</div>
					<br/><a style="color: #888; padding-top: 15px; padding: 10px; font-size: 14px; color: '.$color.'"> '.$message.' </a><br/><br/><br/>
					<a href="search_book.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}

function printInsertUserMessage($mode) {/* prints a message for inserting user */
	$str = "SUCCESS";
	$message = "The user has been inserted successfuly";
	$color = "green";
	if($mode == 0) {
		$message = "User inserting has been failed";
		$color = "red";
		$str = "FAILURE";
	} else if($mode == 2) {
		$message = "The user already exist!!";
		$color = "red";
		$str = "FAILURE";
	}
	echo '<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated zoomIn" style="max-width: 300px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">'.$str.'</h2>
					</div>
					<br/><a style="color: #888; padding-top: 15px; padding: 10px; font-size: 14px; color: '.$color.'"> '.$message.' </a><br/><br/><br/>
					<a href="add_user_page.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}

function printUpdateUserMessage($mode) {/* prints a message for modifying user */
	$str = "SUCCESS";
	$message = "The user has been updated successfuly";
	$color = "green";
	if($mode == 0) {
		$message = "User updating has been failed";	
		$color = "red";
		$str = "FAILURE";
	}
	echo '<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated zoomIn" style="max-width: 300px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">'.$str.'</h2>
					</div>
					<br/><a style="color: #888; padding-top: 15px; padding: 10px; font-size: 14px; color: '.$color.'"> '.$message.' </a><br/><br/><br/>
					<a href="list_users.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}

function printDeleteUserMessage($mode) {/* prints a message for deleting user */
	$str = "SUCCESS";
	$message = "The user has been deleted successfuly";
	$color = "green";
	if($mode == 0) {
		$message = "User deleting has been failed";
		$color = "red";
		$str = "FAILURE";
	}
	echo '<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated zoomIn" style="max-width: 300px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">'.$str.'</h2>
					</div>
					<br/><a style="color: #888; padding-top: 15px; padding: 10px; font-size: 14px; color: '.$color.'"> '.$message.' </a><br/><br/><br/>
					<a href="list_users.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}
?>