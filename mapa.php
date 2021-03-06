<?php 
	
	if (!empty($_GET["var"]))
		$var = $_GET["var"];
	else
		$var = 1;

	if (!empty($_GET["atc"]))
		$atc = $_GET["atc"];
	else
		$atc = 0;

	if (!empty($_GET["cad"]))
		$cad = $_GET["cad"];
	else
		$cad = 0;

	if (!empty($_GET["prt"]))
		$prt = $_GET["prt"];
	else
		$prt = 0;

    if (!empty($_GET["ocp"]))
        $ocp = $_GET["ocp"];
    else
        $ocp = 0;

    if (!empty($_GET["mec"]))
        $mec = $_GET["mec"];
    else
        $mec = 0;

    if (!empty($_GET["mod"]))
        $mod = $_GET["mod"];
    else
        $mod = 0;

    if (!empty($_GET["pfj"]))
        $pfj = $_GET["pfj"];
    else
        $pfj = 0;

    if (!empty($_GET["typ"]))
        $typ = $_GET["typ"];
    else
        $typ = 0;

    if (!empty($_GET["prc"]))
        $prc = $_GET["prc"];
    else
        $prc = 0;

    if (!empty($_GET["uf"]))
        $uf = $_GET["uf"];
    else
        $uf = 0;

	if (!empty($_GET["ano"]))
		$ano = $_GET["ano"];
	else
		$ano = 2014;
    if (!empty($_GET["mundo"]))
        $mundo = $_GET["mundo"];
    else
        $mundo = 0;
    
    if (!empty($_GET["slc"]))
        $slc = $_GET["slc"];
    else
        $slc = 0;
?>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="./css/jquery-jvectormap-2.0.3.css">

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="./js/jquery-jvectormap-2.0.3.min.js"></script>
  <script src="./js/continents-mill.js"></script>
<![endif]-->

<!-- TopoJSON -->
<script src="https://d3js.org/topojson.v2.min.js"></script>

<!-- D3 JS v4 -->
<script src="js/d3/d3.min.js"></script>
<script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.21.0/d3-legend.min.js"></script>

<!-- D3 QUEUE -->
<script src="https://d3js.org/d3-queue.v3.min.js"></script>

<!-- Utilidades -->
<script src="js/functions.js"></script>

<!--================== SVG! =================-->
<?php
    if($eixo == "comercio" && $mundo == 0) {
        echo "<div id=\"corpo-mundi\" style=\"width: 100%; height: 400px\" class=\"mapa fadeIn\"></div>";
    }
    else {
        echo "<div id=\"corpo\" class=\"mapa fadeIn\"></div>";
    }
?>

<script type="text/javascript">

	//variaveis configuracao query
	var vrv = <?php echo $var; ?>;
	var atc = <?php echo $atc; ?>;
	var cad = <?php echo $cad; ?>;
    var ocp = <?php echo $ocp; ?>;
	var prt = <?php echo $prt; ?>;
    var mec = <?php echo $mec; ?>;
    var mod = <?php echo $mod; ?>;
    var pfj = <?php echo $pfj; ?>;
    var typ = <?php echo $typ; ?>;
    var prc = <?php echo $prc; ?>;
    var ano = <?php echo $ano; ?>;
    var uf = <?php echo $uf; ?>;
    var slc = <?php echo $slc; ?>;
    var mundo = <?php echo $mundo; ?>;
	var eixo;
	switch(window.location.hash.substring(1)) {
        case "empreendimentos":
            eixo = 0;
            break;
        case "mercado":
            eixo = 1;
            break;
        case "politicas":
            eixo = 2;
            break;
        case "comercio":
            eixo = 3;
            break;
        default:
            eixo = 0;
    }
						
</script>

<?php
    if($eixo == "comercio" && $mundo == 0) {
        echo "<script src=\"js/mapa_mundi.js\"></script>";
    }
    else {
        echo "<script src=\"js/mapa.js\"></script>";
    }
?>