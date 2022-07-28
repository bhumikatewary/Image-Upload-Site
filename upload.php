<?php
if (isset($_POST['submit']) && isset($_FILES['my_image'])){

   include "db_conn.php";
    
   echo "<pre>";
   print_r($_FILES['my_image']);

   echo "Image upload successful!";

   echo "</pre>";


   $img_name = $_FILES['my_image']['name'];
   $img_size = $_FILES['my_image']['size'];
   $tmp_name = $_FILES['my_image']['tmp_name'];
   $error = $_FILES['my_image']['error'];

   if($error === 0){
      if($img_size > 125000){
         $em = "File size too big!";
       header("Location: file.php?error=$em");
      }else{
         $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
         $img_ex_lc = strtolower($img_ex);

         $allowed_exs = array("jpg", "jpeg", "png");

         if(in_array($img_ex_lc, $allowed_exs)){
            $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
            $img_upload_path = 'gallery/'.$new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path);
            $em = "Image successfully uploaded!";
            header("Location: file.php?error=$em");


            //INSERT INTO DATABASE

            $sql = "INSERT INTO images(image_url) 
                    VALUES('$new_img_name')";
            mysqli_query($conn, $sql);
            header("Location: view.php");

         }else{
            $em = "Cant upload files of this type!";
       header("Location: file.php?error=$em");
         }
      }

   }else{
      $em = "unknown error occured!";
       header("Location: file.php?error=$em");
   }

}else{
   header("Location: file.php");
}






