
<div class="card-body text-center bg-dark text-light p-2">
    <h5>Comments</h5>
  </div>

<div class="container">
  <?php 
  if (isset($comment_type)) {
    if (isset($comment_of)) {
      if (isset($_POST['comment'])) {
        if (create_comment($connect,$comment_of,$_POST['creator'],$_POST['comment'],$_POST['file'],$comment_type)==1) {
          message("Comment Posted!",1);
        }
      }
    } else {
      message('Please set $comment_of value!',0);
    }
  } else {
    message('Please set $comment_type value!',0);
  }


  if (isset($_GET['del-comment'])) {
    $delComment = delete_comment($connect,$_GET['del-comment']);
  } ?>
<br>
  <form method="post">
    <div class="form-group">
      <div class="form-group">
        <textarea class="textarea_editor form-control" rows="5" placeholder="Enter text ..." name="comment"></textarea>
      </div>
    </div>

    <div class="custom-file">
      <input type="file" class="custom-file-input" id="attachment">
      <label class="custom-file-label" for="attachment">Choose file</label>
    </div>

    <div class="list-group" id="upload">
    </div>

    <input id="file" name="file" type="hidden"/>
    <input id="creator" name="creator" value="<?php echo $_SESSION['id'];?>" type="hidden"/>
    <div id="list">

    </div>
    <br>
    <div class="form-group" align="right"><button class="btn btn-primary">Post Comment</button></div>
  </form>


  <?php 
  if (isset($comment_type) && isset($comment_of) && isset($query_string)) {
      $comments = get_comments($connect,$comment_type,$comment_of);
      if ($comments->num_rows>0) {
      while ($comment = mysqli_fetch_array($comments)) {?>
        <div class="card">
          <div class="card-header">
            <img src="<?php echo get_user_data($connect,$comment['creator'])['picture'];?>" class="rounded-circle w-cover" width="50" />  <b><?php echo get_user_data($connect,$comment['creator'])['name'];?></b> <cite title="Source Title">(<?php echo date("Y-m-d",strtotime($comment['creation_date']));?>)</cite>
            <button class="btn btn-light" type="button" id="option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></button>
            <div class="dropdown-menu" aria-labelledby="option">
              <?php if ($_SESSION['role'] ==1 || $data['creator']==$_SESSION['id']) {?>
                <a class="dropdown-item" href="?<?php echo $query_string;?>&del-comment=<?php echo $comment['id'];?>">Delete</a>
              <?php } ?>
            </div>
          </div>
          <div class="card-body">
            <blockquote class="blockquote mb-0">
              <p><?php echo base64_decode($comment['comment']);?></p>
              <footer class="blockquote-footer">

               Attachments: <br/>
               <?php $i = 0; $files = explode(',',$comment['file']); foreach($files as $file){ echo '<a href="uploads/'.$file.'">'.$file.'</a><br/>'; $i++;}?>
             </footer>
           </blockquote>
         </div>
       </div>
     <?php } } 
   } else {
    message('Please set $comment_type, $comment_of and $query_string value!',0);
  }
?>

</div>

<script type="text/javascript">
  $('#attachment').on('change', function() {
    var file_data = $('#attachment').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);
    $('.custom-file').hide();
    $('#upload').append('<div id="loading" class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>');              
    $.ajax({
        url: 'upload.php', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(php_script_response){
          $('#attachment').val('');
          $('.custom-file').show();
          $('#upload').empty();
          if ($('#file').val().length>0) { var sep = ","; } else {var sep = "";}
          $('#file').val($('#file').val()+sep+php_script_response);
            $('#list').append('<div class="list-group-item"><a href="uploads/'+php_script_response+'">'+php_script_response+'</a></div>'); // display response from the PHP script, if any
          }
        });
  });

</script>
<script type="text/javascript">
  $("#finish").click(function(){
          $("#delete_comment").css("opacity:0.5");
          $.ajax({url: "status.php?task&finish="+id, success: function(result){
            if (result==1) {
              $("#finish").hide();
              $("#start").hide();
              $("#resume").show();
              $("#return").show();
              $("#accept").show();
            }
          }});
        });
</script>
