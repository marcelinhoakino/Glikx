<?php

include_once 'conexao.php';

$glic = $_GET["glic"];
$carbo= $_GET["carbo"];
$apl= $_GET["apl"];
$obs= $_GET["obs"];

// calibração 
$fatcarbo = 20;
$fatglic = 80;
$alvo = 120;
$ult_calib = "19/03/2024";

$apl1 = 0;
$apl2 = 0;

///// calculos se for postado ///
if (isset($_GET['glic'])){

    if ($glic<=$alvo){
        $apl1 = 0;
        //echo $apl1;
    } else {
        $apl1 = ($glic - $alvo) / $fatglic;        
        //echo $apl1;
    } 

    if ($carbo<$fatcarbo){
        $apl2 = 0;
        //echo $apl1;
    } else {
        $apl2 = $carbo / $fatcarbo;        
        //cho $apl2;
    } 

    $apl = $apl1 + $apl2;

    //começa salvar
    $data = date('Y-m-d');
    $hora =  date('s:i:H');
    $sql = "INSERT INTO `glikx` (`id`, `data`, `hora`, `glic`, `carb`, `aplic`, `fatglic`, `fatcarb`, `obs`) VALUES (NULL, '$data', '$hora', '$glic', '$carbo', '$apl', '$fatglic', '$fatcarbo', '$obs');";
    if (mysqli_query($conn, $sql)) {
        echo "<script>window.location.replace('https://aquino.dev.br/bzd');</script>";
        } else {
        echo "Deu merda, avisa o pai!";
    }
    mysqli_close($conn);

}
?>
<!DOCTYPE html>
<html lang="pt-br" >
<head>
  <meta charset="UTF-8">
  <title>GlikX 1.0 ♥ Bzd</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <?php
        $sql = "SELECT * FROM `glikx` ORDER BY `glikx`.`id` DESC LIMIT 0,1 ";
        $result = $conn->query($sql);
        //id`, `data`, `hora`, `glic`, `carb`, `aplic`, `fatglic`, `fatcarb`, `obs`
        
        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
            $data   = $row["data"];
            $hora   = $row["hora"];
            $glic   = $row["glic"];
            $carb   = $row["carb"];
            $apl    = $row["aplic"];
            $obs    = $row["obs"];
          }
        } else {
          echo "0 results";
        }
        $conn->close();
    ?>
<!-- partial:index.partial.html -->
<main>
	<form name="GlikX" action="">
		<h1><a href="https://www.aquino.dev.br/bzd">GlikX</a></h1>
			
		<div class="form-group">
			<label for="glic">Glicemia</label>
			<input inputmode="numeric" onclick="zerar()" type="number" id="glic" name="glic" class="form-control" min="30" step="1"  max="600" required placeholder="<?php echo $glic;?>"/>
		</div>
		
				<div class="form-group" style="width:48%; float:left; margin-bottom:20px;">
			<label for="carbo">g carbo</label>
			<input  inputmode="numeric" type="number" id="carbo" name="carbo" min="0" step="1"  max="200" class="form-control"  placeholder="<?php echo $carb;?>"/>
		</div>
    
    <div class="form-group" style="width:48%; float:right; margin-bottom:20px;">
            <label for="calib">obs</label>
            <input type="text" id="obs" name="obs" class="form-control" placeholder="<?php echo $obs;?>" />        
    </div>
       <div id="reg" style="display:block; text-align:center;">Último registro : <?php echo  implode("/",array_reverse(explode("-",$data))); ?> às <?php  echo    substr(implode(":",array_reverse(explode(":",$hora))), 0, 5); ?></div>
        <div class="form-group">
            <input type="submit" id="calc" value="Aplicar <?php echo $apl;?> unidades" class="btn btn-info" style="padding:30px; width:100%; font-size:30px;"></input>
		</div>
		
		<!--div class="form-group">
			<label for="apl">Aplicar Insulina (und)</label>
			<input type="text" id="apl" name="apl" readonly  class="form-control" style="display:block; font-size:30px;" value="Aplicar <?php echo $apl; ?> unidades" />
		</div-->
		

        <div class="form-group">
        <h1></h1><br>
            <label for="calib" style="font-size:16px;">Calibração Atual (<?php echo $ult_calib;?>)</label>
            <input style="width:100%;"  disabled class="form-control" value="1 und para cada <?php echo $fatcarbo;?>g de carbo"/>
			<input style="width:100%;"  disabled class="form-control" value="1 und cada <?php echo $fatglic;?> maior que <?php echo $alvo?>"/><br>
            <h1></h1><br>
            by @marcelinhoakino ♥ Bzd
        </div>
	</form>
</main>

<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script>
        function zerar(){
            document.getElementById('calc').value='CALCULAR'; // Limpa o campo
            document.getElementById('calc').classList.add("btn-success");
            document.getElementById('calc').classList.remove("btn-info");
            document.getElementById('reg').style.display='none';
            document.getElementById('obs').placeholder='';
        }       
    </script>

</body>
</html>
