<style>
	.centrarimagen
	{
		position: absolute;
		top:30%;
		left:50%;
		width:560px;
		margin-left:-280px;
		height:370px;
		margin-top:-185px;
		padding:5px;
	}
	.centrarimagen
	/*{
		position: absolute;
		top:-20px;
		width:80%;
		/*margin-left:-280px;*/
		height:100vh;
		/*margin-top:-185px;*/
		margin:0px;
		height:80%;
		/*background-image: url("imagenes/login.jpg");*/
		background-repeat: repeat-y !important;
	}*/
	.fondo_comu
	{
		background-image: url("imagenes/sf.jpg");
		background-size:     cover;                      /* <------ */
    	background-repeat:   no-repeat;
    	background-position: center center;              /* optional, center the image */
	}
	#alpha {
  background-color: rgba(0, 0, 0, .5);
  width: 150px;
  /*position: absolute;*/
  top: 10px;
  color: #fff;
  padding-top: 1em;
}



.carousel-item {
  height: 80vh;
  min-height: 300px;
  background: no-repeat center center scroll;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

.card-columns .card {
  display: inline-block;
  width: 100%;
}

.carousel-item img {
  width: 100%;
  height: 80vh;
  border-top-left-radius: calc(0.25rem - 1px);
  border-top-right-radius: calc(0.25rem - 1px);   
}


.carousel-control-prev,.carousel-control-next{
  background: #6E088A !important;
}


</style>
<?php
include("datosUsuario.php");
	echo " <div class='centrarimagen'>
		<center><img src='imagenes/".$logoTiendaRopa."'  width='80%' heigth='80%'></center>
	</div>";
?>