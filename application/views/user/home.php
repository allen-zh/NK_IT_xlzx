<html>
<head>
  <title><?php echo $title ?></title>
</head>
<body>
  <h2>zh</h2>
<?php echo validation_errors(); ?>

<?php echo form_open('reservation'); ?>

<h5>tid</h5>
<input type="text" name="tid" value="" size="50" />
<h5>date</h5>
<input type="text" name="date" value="" size="50" />
<h5>type</h5>
<input type="text" name="type" value="" size="50" />
<h5>info</h5>
<input type="text" name="info" value="" size="50" />
<div><input type="submit" value="Submit" /></div>

</form>


</body>
</html>