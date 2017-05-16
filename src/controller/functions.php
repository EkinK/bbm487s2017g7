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
		
		/* give book to users */
		
		$query2 = "select userID from waitings where bookID = ".$b_id." order by waitingID asc";
		$users= mysqli_query($_SESSION["conn"], $query2);
		
		if(mysqli_num_rows($users)) {
			
			$counter = 0;
			while($row = mysqli_fetch_array($users)){
				
				$res = borrowBook($row["userID"], $b_id);
				if($res == 1) $counter++;
				
				if($counter >= $quantity) break;
			}
		}
		
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

function getAvailableBooks($str) {
	/* returns records has str in its' title or authors' name/surname */
	$query = "select b.bookID, b.title, b.stock_num, a.a_name, a.a_surname, p.p_name
			from books b 
				inner join authors a on b.authorID = a.authorID
				inner join publishers p on p.publisherID = b.publisherID
			where b.stock_num > 0 and (title like '%".$str."%' or a.a_name like '%".$str."%' or a.a_surname like '%".$str."%' )";
			
	$sql = mysqli_query($_SESSION["conn"], $query);
	
	return $sql;
}

function getUnavailableBooks($str) {
	/* returns records has str in its' title or authors' name/surname */
	$query = "select b.bookID, b.title, b.stock_num, a.a_name, a.a_surname, p.p_name
			from books b 
				inner join authors a on b.authorID = a.authorID
				inner join publishers p on p.publisherID = b.publisherID
			where b.stock_num = 0 and (title like '%".$str."%' or a.a_name like '%".$str."%' or a.a_surname like '%".$str."%' )";
			
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
function listBooks($str) { /* lists all books for admin */
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
							<input type="hidden" id="title" name="title" value="'.$row['title'].'">
							<input type="hidden" id="a_name" name="a_name" value="'.$row["a_name"].'">
							<input type="hidden" id="a_surname" name="a_surname" value="'.$row["a_surname"].'">
							<input type="hidden" id="publisher" name="publisher" value="'.$row['p_name'].'">
							<input type="hidden" id="quantity" name="quantity" value="'.$row['stock_num'].'">
							<button class="modify_btn" type="submit" style="letter-spacing: 0px;">UPDATE</button>
						</form>
					</td>
					<td>
						<form action="delete_book_confirm.php" method="POST">
							<input type="hidden" id="bookID" name="bookID" value="'.$row['bookID'].'">
							<input type="hidden" id="title" name="title" value="'.$row['title'].'">
							<input type="hidden" id="author" name="author" value="'.$row["a_name"].' '.$row["a_surname"].'">
							<input type="hidden" id="publisher" name="publisher" value="'.$row['p_name'].'">
							<input type="hidden" id="quantity" name="quantity" value="'.$row['stock_num'].'">
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

function listAvailableBooks($str) { /* lists all available books for user */
	
	$books = getAvailableBooks($str);
	
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
						<form action="borrow_book_confirm.php" method="POST">
							<input type="hidden" id="bookID" name="bookID" value="'.$row['bookID'].'">
							<input type="hidden" id="title" name="title" value="'.$row['title'].'">
							<input type="hidden" id="author" name="author" value="'.$row["a_name"].' '.$row["a_surname"].'">
							<input type="hidden" id="publisher" name="publisher" value="'.$row['p_name'].'">
							<button class="modify_btn" type="submit" style="letter-spacing: 0px;">BORROW</button>
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

function listUnavailableBooks($str) { /* lists all available books for user */
	
	$books = getUnavailableBooks($str);
	
	if(mysqli_num_rows($books)) {	/* any records exist? */
		/* start printing table */
		echo '<tr>
				<th>Title</th>
				<th>Author</th>
				<th>Publisher</th>
				<th>Queue Length</th>
				<th></th>
			</tr>
		   <tbody>';
		while($row = mysqli_fetch_array($books)) {	/* for every book */
		
			$query = "select count(*) as queue_length 
						from waitings
						where bookID = ".$row['bookID']."";
			
			$queue_length = mysqli_fetch_array(mysqli_query($_SESSION["conn"], $query))['queue_length'];
			
			/* show book properties */
			echo '<tr>
					<td>'.$row["title"].'</td>
					<td>'.$row["a_name"].' '.$row["a_surname"].'</td>
					<td>'.$row["p_name"].'</td>
					<td>'.$queue_length.'</td>
					<td>
						<form action="queue_up_confirm.php" method="POST">
							<input type="hidden" id="bookID" name="bookID" value="'.$row['bookID'].'">
							<input type="hidden" id="title" name="title" value="'.$row['title'].'">
							<input type="hidden" id="author" name="author" value="'.$row["a_name"].' '.$row["a_surname"].'">
							<input type="hidden" id="publisher" name="publisher" value="'.$row['p_name'].'">
							<button class="modify_btn" type="submit" style="letter-spacing: 0px;">QUEUE UP</button>
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
	
	$query = "delete from borrows where bookID = ".$id."";
	mysqli_query($_SESSION["conn"], $query);
	
	$query = "delete from waitings where bookID = ".$id."";
	mysqli_query($_SESSION["conn"], $query);
	
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
		
		if($old_quantity == 0 && $quantity != NULL) {
			
			$query2 = "select userID from waitings where bookID = ".$bookID." order by waitingID asc";
			$users= mysqli_query($_SESSION["conn"], $query2);
			
			if(mysqli_num_rows($users)) {
				
				$counter = 0;
				while($row = mysqli_fetch_array($users)){
					
					$res = borrowBook($row["userID"], $bookID);
					if($res == 1) $counter++;
					
					if($counter >= $quantity) break;
				}
			}
		}
		return 1;
	}
	else {
		return -1;
	}
}

function deleteBorrow($userID, $bookID, $quantity){
	
	$query = "select borrowID
				from borrows
				where bookID = ".$bookID." and userID = ".$userID."";
	
	$sql = mysqli_query($_SESSION["conn"], $query);
	
	if(mysqli_num_rows($sql)) {
		
		$borrowID = mysqli_fetch_array($sql)['borrowID'];
		
		return returnBook($borrowID, $bookID, $quantity); /* returns 1 for success or -1 for fail */
	}else  {
		// failed
		return -1;
	}
				
	returnBook(mysqli_fetch_array()['borrowID'], $bookID, $quantity);
}

function deleteSelectedUser($id) {
	
	/* delete user from queue */
	$query = "delete from waitings where userID = ".$id."";
	mysqli_query($_SESSION["conn"], $query);
	
	/* get bookID user borrowed */
	$query = "select borrowID, bookID from borrows where userID = ".$id."";
	$books = mysqli_query($_SESSION["conn"], $query);
	
	/* force user return books s/he borrowed */
	if(mysqli_num_rows($books)) {
		while($row = mysqli_fetch_array($books)) {
				$query = "select quantity from books where bookID = ".$row['bookID']."";
				$sql = mysqli_query($_SESSION["conn"], $query);
				$quantity = mysqli_fetch_array($books)["quantity"];
				
				returnBook($row["borrowID"], $row["bookID"], $quantity);
		}
	}
	
	/* delete book */
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

function listUserBooks($userID) {
	
	$query = "select br.borrowID, br.bookID, b.title, b.a_name, b.a_surname, b.p_name, b.stock_num, br.borrow_date, br.delivery_date
			from (select b.bookID, b.title, b.stock_num, a.a_name, a.a_surname, p.p_name
					from books b 
					inner join authors a on b.authorID = a.authorID
					inner join publishers p on p.publisherID = b.publisherID ) b
				inner join borrows br on br.bookID=b.bookID
			where br.userID = ".$userID."";
	
	$sql = mysqli_query($_SESSION["conn"], $query);
	
	if(mysqli_num_rows($sql)) {	/* any records exist? */
		/* start printing table */
		echo '<tr>
				<th>Title</th>
				<th>Author</th>
				<th>Publisher</th>
				<th>Borrow Date</th>
				<th>Expire Date</th>
				<th></th>
			</tr>
		   <tbody>';
		while($row = mysqli_fetch_array($sql)) {	/* for every book */
			/* show book properties */
			echo '<tr>
					<td>'.$row["title"].'</td>
					<td>'.$row["a_name"].' '.$row["a_surname"].'</td>
					<td>'.$row["p_name"].'</td>
					<td>'.$row["borrow_date"].'</td>
					<td>'.$row["delivery_date"].'</td>
					<td>
						<form action="return_book_confirm.php" method="POST">
							<input type="hidden" id="borrowID" name="borrowID" value="'.$row['borrowID'].'">
							<input type="hidden" id="bookID" name="bookID" value="'.$row['bookID'].'">
							<input type="hidden" id="title" name="title" value="'.$row['title'].'">
							<input type="hidden" id="author" name="author" value="'.$row["a_name"].' '.$row["a_surname"].'">
							<input type="hidden" id="publisher" name="publisher" value="'.$row['p_name'].'">
							<input type="hidden" id="quantity" name="quantity" value="'.$row['stock_num'].'">
							<button class="modify_btn" type="submit" style="letter-spacing: 0px;">RETURN</button>
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

function listUserWaitings($userID) {
	
	$query = "select w.waitingID, w.bookID, b.title, b.a_name, b.a_surname, b.p_name, b.stock_num
			from (select b.bookID, b.title, b.stock_num, a.a_name, a.a_surname, p.p_name
					from books b 
					inner join authors a on b.authorID = a.authorID
					inner join publishers p on p.publisherID = b.publisherID ) b
				inner join waitings w on w.bookID=b.bookID
			where w.userID = ".$userID."";
	
	$sql = mysqli_query($_SESSION["conn"], $query);
	
	if(mysqli_num_rows($sql)) {	/* any records exist? */
		/* start printing table */
		echo '<tr>
				<th>Title</th>
				<th>Author</th>
				<th>Publisher</th>
				<th>Users left</th>
				<th></th>
			</tr>
		   <tbody>';
		while($row = mysqli_fetch_array($sql)) {	/* for every book */
		
			$query = "select count(*) as queue_length
						from waitings
						where bookID = ".$row['bookID']." and userID < ".$userID."";
			
			$queue_length = mysqli_fetch_array(mysqli_query($_SESSION["conn"], $query))['queue_length'];
		
			/* show book properties */
			echo '<tr>
					<td>'.$row["title"].'</td>
					<td>'.$row["a_name"].' '.$row["a_surname"].'</td>
					<td>'.$row["p_name"].'</td>
					<td>'.$queue_length.'</td>
					<td>
						<form action="cancel_queue_up_confirm.php" method="POST">
							<input type="hidden" id="bookID" name="bookID" value="'.$row['bookID'].'">
							<input type="hidden" id="title" name="title" value="'.$row['title'].'">
							<input type="hidden" id="author" name="author" value="'.$row["a_name"].' '.$row["a_surname"].'">
							<input type="hidden" id="publisher" name="publisher" value="'.$row['p_name'].'">
							<input type="hidden" id="queue_length" name="queue_length" value="'.$queue_length.'">
							<button class="modify_btn" type="submit" style="letter-spacing: 0px;">CANCEL</button>
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

function listBorrowedBooks() {
	
	$query = "select br.borrowID, br.bookID, br.userID, u.username, b.title, b.a_name, b.a_surname, b.p_name, b.stock_num, br.borrow_date, br.delivery_date
			from (select b.bookID, b.title, b.stock_num, a.a_name, a.a_surname, p.p_name
					from books b 
					inner join authors a on b.authorID = a.authorID
					inner join publishers p on p.publisherID = b.publisherID ) b
				inner join borrows br on br.bookID=b.bookID
				inner join users u on u.userID = br.userID";
			
	$borrows = mysqli_query($_SESSION["conn"], $query);
	
	if(mysqli_num_rows($borrows)) {	/* any records exist? */
		/* start printing table */
		echo '<tr>
				<th>Username</th>
				<th>Title</th>
				<th>Author</th>
				<th>Publisher</th>
				<th>Borrow Date</th>
				<th>expire Date</th>
				<th></th>
			</tr>
		   <tbody>';
		while($row = mysqli_fetch_array($borrows)) {	/* for every book */
			/* show book properties */
			echo '<tr>
					<td>'.$row["username"].'</td>
					<td>'.$row["title"].'</td>
					<td>'.$row["a_name"].' '.$row["a_surname"].'</td>
					<td>'.$row["p_name"].'</td>
					<td>'.$row["borrow_date"].'</td>
					<td>'.$row["delivery_date"].'</td>
					<td>
						<form action="delete_borrow_confirm.php" method="POST">
							<input type="hidden" id="userID" name="userID" value="'.$row['userID'].'">
							<input type="hidden" id="bookID" name="bookID" value="'.$row['bookID'].'">
							<input type="hidden" id="username" name="username" value="'.$row['username'].'">
							<input type="hidden" id="title" name="title" value="'.$row['title'].'">
							<input type="hidden" id="author" name="author" value="'.$row["a_name"].' '.$row["a_surname"].'">
							<input type="hidden" id="publisher" name="publisher" value="'.$row['p_name'].'">
							<input type="hidden" id="quantity" name="quantity" value="'.$row['stock_num'].'">
							<input type="hidden" id="borrow_date" name="borrow_date" value="'.$row['borrow_date'].'">
							<input type="hidden" id="delivery_date" name="delivery_date" value="'.$row['delivery_date'].'">
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

function returnBook($borrowID, $bookID, $quantity) {
	
	$query = "delete from borrows where borrowID = ".$borrowID."";
	
	if(mysqli_query($_SESSION["conn"], $query)) {
		//success
		$new_quantity = $quantity + 1;
		$update = "update books
				set stock_num=".$new_quantity."
				where bookID = ".$bookID."";
		
		mysqli_query($_SESSION["conn"], $update);
		
		/* get users waiting for the specified books */
		$query = "select userID from waitings where bookID = ".$bookID." order by waitingID asc";
		$users = mysqli_query($_SESSION["conn"], $query);
		
		if(mysqli_num_rows($users)) { /* any record exist? */
			
			/* give book to first available user from waiting list */
			while($row = mysqli_fetch_array($users)) {
				
				if (borrowBook($row["userID"], $bookID) == 1) break;
			}
		}
		
		return 1;
	}else {
		// failed
		return -1;
	}
}

function borrowBook($userID, $bookID) {
	
	/* get number of books user have borrowed */
	$control_query = "select count(*) as borrow_count 
						from borrows 
						where userID = ".$userID."";
						
	$borrow_check_sql = mysqli_query($_SESSION["conn"], $control_query);
	
	if(mysqli_fetch_array($borrow_check_sql)["borrow_count"] >= 3) { 
		/* users cannot borrow more than 3 books */
		return -1;
	}
	
	
	/* check if user already have the same book */
	$control_query2 = "select * 
						from borrows 
						where userID = ".$userID." and bookID = ".$bookID."";
						
	$borrow_check_sql2 = mysqli_query($_SESSION["conn"], $control_query2);
	
	if(mysqli_num_rows($borrow_check_sql2)) {
		/* book is already borrowed by the same user */
		return -1;
	}
	
	
	/* else borrowing is available */
	$query = "insert into borrows(userID, bookID) values(".$userID.", ".$bookID.")";
	
	if(mysqli_query($_SESSION["conn"], $query)) {
		/* borrowing success */
		
		/* get stock count of book */
		$query = "select stock_num 
					from books 
					where bookID = ".$bookID."";
		
		$quantity = mysqli_fetch_array(mysqli_query($_SESSION["conn"], $query))["stock_num"];
		$new_quantity = $quantity - 1;
		
		/* reduce stock count of book */
		$update = "update books
				set stock_num=".$new_quantity."
				where bookID = ".$bookID."";
		
		mysqli_query($_SESSION["conn"], $update);
		
		
		/* delete the request from waiting list if exists */
		$delete_waiting = "delete from waitings 
							where userID = ".$userID." and bookID = ".$bookID."";
							
		mysqli_query($_SESSION["conn"], $delete_waiting);
		
		
		return 1;
	}else {
		/* borrowing failed */
		return -1;
	}
}

function queueUp($userID, $bookID) {
	
	/* get number of books user have queued up */
	$control_query = "select count(*) as book_count 
						from waitings 
						where userID = ".$userID."";
						
	$queue_check_sql = mysqli_query($_SESSION["conn"], $control_query);
	
	if(mysqli_fetch_array($queue_check_sql)["book_count"] >= 10) { 
		/* users cannot queue up more than 10 books */
		return -1;
	}
	
	/* check if user already have the same book */
	$control_query2 = "select * 
						from borrows 
						where userID = ".$userID." and bookID = ".$bookID."";
						
	$borrow_check_sql2 = mysqli_query($_SESSION["conn"], $control_query2);
	
	if(mysqli_num_rows($borrow_check_sql2)) {
		/* book is already borrowed by the same user */
		return -1;
	}
	
	/* check if user already in queue for the same book */
	$control_query3 = "select * 
						from waitings 
						where userID = ".$userID." and bookID = ".$bookID."";
						
	$borrow_check_sql3 = mysqli_query($_SESSION["conn"], $control_query3);
	
	if(mysqli_num_rows($borrow_check_sql3)) {
		/* the user is already in queue */
		return -1;
	}
	
	/* else queue up is available */
	$query = "insert into waitings(userID, bookID) values(".$userID.", ".$bookID.")";
	
	if(mysqli_query($_SESSION["conn"], $query)) {
		/* success */
		return 1;
	}else {
		/* failed */
		return -1;
	}
}

function cancelQueueUp($userID, $bookID) {
	
	$query = "delete from waitings where userID = ".$userID." and bookID = ".$bookID."";
	
	if(mysqli_query($_SESSION["conn"], $query)) {
		/* success */
		return 1;
	}else {
		/* failed */
		return -1;
	}
}

/* print messages will be optimized later.. */

function printDeleteBorrowMessage($mode) {
	$str = "SUCCESS";
	$message = "The borrow is successfully deleted";
	$color = "green";
	if($mode == 0) {
		$message = "Borrow deleting has failed";
		$color = "red";
		$str = "FAILURE";
	}
	echo '<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated zoomIn" style="max-width: 320px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">'.$str.'</h2>
					</div>
					<br/><a style="color: #888; padding-top: 15px; padding: 10px; font-size: 14px; color: '.$color.'"> '.$message.' </a><br/><br/><br/>
					<a href="list_loaned_books.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}

function printCancelQueueUpMessage($mode) {
	$str = "SUCCESS";
	$message = "You are successfully removed from waiting list";
	$color = "green";
	if($mode == 0) {
		$message = "Removing from waiting list has been failed";
		$color = "red";
		$str = "FAILURE";
	}
	echo '<div class="container">
			<div class="top">
				<br/><br/><br/><br/><br/><h1 id="title"></h1>
				<div class="login-box animated zoomIn" style="max-width: 320px;">
					<div class="box-header">
						<h2 style="letter-spacing: 0px; font-size: 1.3em;">'.$str.'</h2>
					</div>
					<br/><a style="color: #888; padding-top: 15px; padding: 10px; font-size: 14px; color: '.$color.'"> '.$message.' </a><br/><br/><br/>
					<a href="user_book.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}

function printQueueUpMessage($mode) {
	$str = "SUCCESS";
	$message = "You are successfully added to waiting list";
	$color = "green";
	if($mode == 0) {
		$message = "Adding to waiting list has been failed";
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
					<a href="books_page.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}

function printBorrowBookMessage($mode) {
	$str = "SUCCESS";
	$message = "The book has been borrowed successfuly";
	$color = "green";
	if($mode == 0) {
		$message = "Borrowing book has been failed";
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
					<a href="books_page.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}

function printReturnBookMessage($mode) {/* prints a message for returning book */
	$str = "SUCCESS";
	$message = "The book has been returned successfuly";
	$color = "green";
	if($mode == 0) {
		$message = "Book return has been failed";
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
					<a href="user_book.php" class="cancel_button">OK</a>
				</div>
			</div>
		</div>';
}

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