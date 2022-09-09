<?php
if (isset($_POST['submit'])) {
    if (isset($_POST['Имя']) && isset($_POST['Email']) &&
        isset($_POST['Телефон']) && isset($_POST['Тема']) &&
        isset($_POST['Сообщение'])) {
        
        $Имя = $_POST['Имя'];
	    $Email = $_POST['Email'];
	    $Телефон = $_POST['Телефон'];
	    $Тема = $_POST['Тема'];
	    $Сообщение = $_POST['Сообщение'];
        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "test1";
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if ($conn->connect_error) {
            die('Не удалось подключиться к базе данных.');
        }
        else {
            $Select = "SELECT Email FROM mydb WHERE Email = ? LIMIT 1";
            $Insert = "INSERT INTO mydb(Имя, Email, Телефон, Тема, Сообщение) values(?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($Select);
            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $stmt->bind_result($resultEmail);
            $stmt->store_result();
            $stmt->fetch();
            $rnum = $stmt->num_rows;
            if ($rnum == 0) {
                $stmt->close();
                $stmt = $conn->prepare($Insert);
                $stmt->bind_param("sssss",$Имя, $Email, $Телефон, $Тема, $Сообщение);
                if ($stmt->execute()) {
                    echo "Новая запись успешно вставлена.";
                }
                else {
                    echo $stmt->error;
                }
            }
            else {
                echo "Кто-то уже регистрировался, используя этот адрес электронной почты.";
            }
            $stmt->close();
            $conn->close();
        }
    }
    else {
        echo "Все поля обязательны для заполнения.";
        die();
    }
}
else {
    echo "Кнопка отправки не установлена";
}
?>