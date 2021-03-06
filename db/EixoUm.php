<?php

###	Classe que manipula as variáveis do Eixo I ###

define('DB_NOME', 'Atlas');
define('DB_USUARIO', 'root');
define('DB_SENHA', 'root');
//define('DB_HOST', 'localhost');
define('DB_HOST', '143.54.231.130');

class EixoUm {

## Atributos ##

	protected static $table = 'Eixo_1';
	private static $conn;

 	//informações Eixo_1
	protected $id;
	public $Numero;
	public $idUF;
	public $idAtuacao;
	public $idCadeia;
	public $idPorte;
	public $Ano;
	public $Valor;
	public $Percentual;
    public $percentual_scc;
    public $percentual_region;
	public $Taxa;

	//informações UF
	public $UFNome;
	public $UFRegiao;
	public $UFSigla;

	//informações Atuação
	public $AtuacaoNome;

	//informações Cadeia
	public $CadeiaNome;
	
	//informações Porte
	public $PorteNome;


## Metodos ##

	
	/*-----------------------------------------------------------------------------
	Função: Connect
	    função para estabelecer conexão do objeto com o banco de dados
	Entrada: 
	    void
	Saída:
	    Positivo = Retorna PDO de conexão com o banco de dados
	    Negativo = Erro de conexão
	-----------------------------------------------------------------------------*/
	public static function connect(){
		$conexao = mysqli_connect(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);

			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}

		self::$conn = $conexao;
		mysqli_query($conexao, 'SET NAMES utf8');
	}

	/*-----------------------------------------------------------------------------
	Função: Disconnect
	    função para desconectar o objeto do banco de dados
	Entrada: 
	    void
	Saída:
	    valor de retorno do mysql_close()
	-----------------------------------------------------------------------------*/
	public static function disconnect(){
		mysqli_close(self::$conn);
	}
	/*-----------------------------------------------------------------------------
	Função: getter_most_recent_year
	    função para buscar o ano mais recente no banco de dados para todas as variáveis
	Saída:
	    Array com os resultados da busca
	-----------------------------------------------------------------------------*/
	public static function getter_most_recent_year(){
		self::connect();

		$query = "SELECT MAX(Ano) AS Ano, Numero FROM `Eixo_1` WHERE `idUF` = 0  GROUP BY Numero";
		$result = mysqli_query(self::$conn, $query);
		
		self::disconnect();

		$allObjects = array();

        while($obj = mysqli_fetch_object($result, 'EixoUm')){
            $allObjects[] = $obj;
        }

		return $allObjects;

	}

	public static function getMaxValueSetor($var, $cad, $prt){
		self::connect();

		$query = "SELECT MAX(Valor) as Valor, Ano FROM ".self::$table
						." WHERE Numero = ".$var
						." AND idCadeia =".$cad
						." AND idPorte =".$prt
						." AND idUF = 0 GROUP BY Ano";
		$result = mysqli_query(self::$conn, $query);

		self::disconnect();
		while($obj = mysqli_fetch_object($result, 'EixoUm')){
            $allObjects[] = $obj;
		}
		
		return $allObjects;
	}

	public static function getAnoDefault($var){
		self::connect();

		$query = "SELECT MAX(Ano) AS Ano FROM `Eixo_1` WHERE `idUF` = 0 AND Numero = ".$var." GROUP BY Numero";
		$result = mysqli_query(self::$conn, $query);
		
		self::disconnect();

		$obj = mysqli_fetch_object($result, 'EixoUm');
		
		$ano = $obj->Ano;

		return $ano;
	}

	/*-----------------------------------------------------------------------------
	Função: Find
	    função para buscar um conjunto de tupla no banco de dados
	Entrada: 
	    $var = número da váriavel 
	    $ufs = id do UF 
	    $atc = id da atuação
	    $cad = id do SCC 
	    $prt = id do porte
	    $anos = ano 
	Saída:
	    Um conjunto de instâncias da Classe EixoUm com seus devidos atributos
	-----------------------------------------------------------------------------*/
	public static function find($var, $ufs, $atc, $cad, $prt, $anos){

		self::connect();

			$query = "SELECT * FROM ".self::$table." AS ex"
					." JOIN UF AS uf ON uf.idUF =  ex.idUF AND uf.idUF = ".$ufs
					." JOIN Atuacao AS atc ON atc.idAtuacao =  ex.idAtuacao AND atc.idAtuacao = ".$atc
					." JOIN Cadeia AS cad ON cad.idCadeia =  ex.idCadeia AND cad.idCadeia = ".$cad
					." JOIN Porte AS prt ON prt.idPorte =  ex.idPorte AND prt.idPorte = ".$prt
					." WHERE ex.Numero = ".$var
					." AND ex.Ano = ".$anos;

			$result = mysqli_query(self::$conn, $query);
			$obj = mysqli_fetch_object($result, 'EixoUm');

		self::disconnect();

        return ($obj == false) ? NULL : $obj;
	}

	/*-----------------------------------------------------------------------------
	Função: All
	    função para buscar todas tupla no banco de dados
	Entrada: 
	    void
	Saída:
	    Todas instancia da Classe EixoUm com seus devidos atributos 
	-----------------------------------------------------------------------------*/
	public static function all(){
		self::connect();
			// $query = "SELECT * FROM ".self::$table." ORDER BY id";
			$query = "SELECT * FROM ".self::$table." AS ex"
						." JOIN UF AS uf ON uf.idUF =  ex.idUF" 
						." JOIN Atuacao AS atc ON atc.idAtuacao =  ex.idAtuacao"
						." JOIN Cadeia AS cad ON cad.idCadeia =  ex.idCadeia"
						." JOIN Porte AS prt ON prt.idPorte =  ex.idPorte"
						." ORDER BY id";

			$result = mysqli_query(self::$conn, $query);
			$allObjects = array();

			while($obj = mysqli_fetch_object($result, 'EixoUm')){
				$allObjects[] = $obj;
			}

		self::disconnect();
		
		return $allObjects;
	}

	/*-----------------------------------------------------------------------------
	Função: Getter Mapa
	    função para obter um conjunto de tuplas para o mapa
	Entrada: 
	    $var = número da váriavel 
	    $atc = id da atuação
	    $cad = id do SCC 
	    $prt = id do porte
	    $anos = ano 
	Saída:
	    Um conjunto de instâncias da Classe EixoUm com seus devidos atributos
	-----------------------------------------------------------------------------*/
	public static function getter_mapa($var, $atc, $cad, $prt, $anos){

		self::connect();
			if($prt == 0 || $cad != 0 || $var == 1 || $var == 3 || $var == 2) {
                $query = "SELECT * FROM " . self::$table . " AS ex"
                    . " JOIN UF AS uf ON uf.idUF =  ex.idUF"
                    . " JOIN Atuacao AS atc ON atc.idAtuacao =  ex.idAtuacao AND atc.idAtuacao = " . $atc
                    . " JOIN Cadeia AS cad ON cad.idCadeia =  ex.idCadeia AND cad.idCadeia = " . $cad
                    . " JOIN Porte AS prt ON prt.idPorte =  ex.idPorte AND prt.idPorte = " . $prt
                    . " WHERE ex.Numero = " . $var;

                $query .= ($anos > 0) ? " AND ex.Ano = " . $anos : "";

                $result = mysqli_query(self::$conn, $query);

                $allObjects = array();

                while($obj = mysqli_fetch_object($result, 'EixoUm')){
                    $allObjects[] = $obj;
                }
            }
            else {
                $query = "SELECT * FROM " . self::$table . " AS ex"
                    . " JOIN UF AS uf ON uf.idUF =  ex.idUF"
                    . " JOIN Atuacao AS atc ON atc.idAtuacao =  ex.idAtuacao AND atc.idAtuacao = " . $atc
                    . " JOIN Porte AS prt ON prt.idPorte =  ex.idPorte AND prt.idPorte = " . $prt
                    . " WHERE ex.Numero = " . $var;

                $query .= ($anos > 0) ? " AND ex.Ano = " . $anos : "";

                $result = mysqli_query(self::$conn, $query);
                $allObjects = array();

                while($obj = mysqli_fetch_object($result, 'EixoUm')){
                    $allObjects[] = $obj;
				}
                $result_aux = array();
                $value_aux = array();
                $percent_aux = array();
                foreach ($allObjects as $data) {
                    if(!isset($value_aux[$data->idUF])) $value_aux[$data->idUF] = 0;
                    if(!isset($percent_aux[$data->idUF])) $percent_aux[$data->idUF] = 0;
                    $value_aux[$data->idUF] += $data->Valor;
                    $percent_aux[$data->idUF] += $data->Percentual;
                    $result_aux[$data->idUF] = $data;
                    $result_aux[$data->idUF]->Valor = $value_aux[$data->idUF];
                    $result_aux[$data->idUF]->Percentual = $percent_aux[$data->idUF];
                }
                $allObjects = $result_aux;

                
            }

		self::disconnect();

        return $allObjects;
	}

	/*-----------------------------------------------------------------------------
	Função: Getter Barras
	    função para obter um conjunto de tuplas para o barras
	Entrada: 
	    $var = número da váriavel 
	    $ufs = id do UF 
	    $atc = id da atuação
	    $cad = id do SCC 
	    $prt = id do porte
	Saída:
	    Um conjunto de instâncias da Classe EixoUm com seus devidos atributosa
	-----------------------------------------------------------------------------*/
	public static function getter_barras($var, $ufs, $atc, $cad, $prt, $uos){

		self::connect();
            if(($prt == 0 || $cad != 0) || $var == 1 || $var == 3 || $var == 2) {
                if($uos == 0) {
                    $query = "SELECT * FROM ".self::$table." AS ex"
                        ." JOIN UF AS uf ON uf.idUF =  ex.idUF AND uf.idUF = ".$ufs
                        ." JOIN Atuacao AS atc ON atc.idAtuacao =  ex.idAtuacao AND atc.idAtuacao = ".$atc
                        ." JOIN Cadeia AS cad ON cad.idCadeia =  ex.idCadeia AND cad.idCadeia = ".$cad
                        ." JOIN Porte AS prt ON prt.idPorte =  ex.idPorte AND prt.idPorte = ".$prt
                        ." WHERE ex.Numero = ".$var;
                }
                else if($uos == 1) {
                    $query = "SELECT * FROM ".self::$table." AS ex"
                        ." JOIN UF AS uf ON uf.idUF =  ex.idUF AND uf.idUF = ".$ufs
                        ." JOIN Atuacao AS atc ON atc.idAtuacao =  ex.idAtuacao AND atc.idAtuacao = ".$atc
                        ." JOIN Cadeia AS cad ON cad.idCadeia =  ex.idCadeia AND cad.idCadeia = 1"
                        ." JOIN Porte AS prt ON prt.idPorte =  ex.idPorte AND prt.idPorte = ".$prt
                        ." WHERE ex.Numero = ".$var;

                }
                $result = mysqli_query(self::$conn, $query);
                $allObjects = array();

                while($obj = mysqli_fetch_object($result, 'EixoUm')){
                    $allObjects[] = $obj;
                }
            }
            else {
                $query = "SELECT * FROM ".self::$table." AS ex"
                    ." JOIN UF AS uf ON uf.idUF =  ex.idUF AND uf.idUF = ".$ufs
                    ." JOIN Atuacao AS atc ON atc.idAtuacao =  ex.idAtuacao AND atc.idAtuacao = ".$atc
                    ." JOIN Porte AS prt ON prt.idPorte =  ex.idPorte AND prt.idPorte = ".$prt
                    ." WHERE ex.Numero = ".$var;

                $result = mysqli_query(self::$conn, $query);
                $allObjects = array();

                while($obj = mysqli_fetch_object($result, 'EixoUm')){
                    $allObjects[] = $obj;
				}
					$result_aux = array();
					$value_aux = array();
					$percent_aux = array();
					foreach ($allObjects as $data) {
						if(!isset($value_aux[$data->Ano])) $value_aux[$data->Ano] = 0;
						if(!isset($percent_aux[$data->Ano])) $percent_aux[$data->Ano] = 0;
						$value_aux[$data->Ano] += $data->Valor;
						$percent_aux[$data->Ano] += $data->Percentual;
						$result_aux[$data->Ano] = $data;
						$result_aux[$data->Ano]->Valor = $value_aux[$data->Ano];
						$result_aux[$data->Ano]->Percentual = $percent_aux[$data->Ano];
					}
					$allObjects = $result_aux;

            }

		self::disconnect();

        return $allObjects;
	}
	

	/*-----------------------------------------------------------------------------
	Função: Getter Region
	    função para obter um conjunto de tuplas para treemap region
	Entrada: 
	    $var = número da váriavel 
	    $atc = id da atuação
	    $cad = id do SCC 
	    $prt = id do porte
	    $anos = ano 
	    $regiao = região do Brasil
	Saída:
	    Um conjunto de instâncias da Classe EixoUm com seus devidos atributos
	-----------------------------------------------------------------------------*/
	public static function getter_region($var, $atc, $cad, $prt, $anos, $regiao){

		self::connect();		
			if($prt == 0 || $cad != 0 || $var == 1 || $var == 3|| $var == 2) {
                $query = "SELECT * FROM " . self::$table . " AS ex"
                    . " JOIN UF AS uf ON uf.idUF =  ex.idUF AND uf.UFRegiao LIKE '" . $regiao . "'"
                    . " JOIN Atuacao AS atc ON atc.idAtuacao =  ex.idAtuacao AND atc.idAtuacao = " . $atc
                    . " JOIN Cadeia AS cad ON cad.idCadeia =  ex.idCadeia AND cad.idCadeia = " . $cad
                    . " JOIN Porte AS prt ON prt.idPorte =  ex.idPorte AND prt.idPorte = " . $prt
                    . " WHERE ex.Numero = " . $var;

                $query .= ($anos > 0) ? " AND Ano = " . $anos : "";

                $result = mysqli_query(self::$conn, $query);
                $allObjects = array();
            }
            else {
                $query = "SELECT * FROM " . self::$table . " AS ex"
                    . " JOIN UF AS uf ON uf.idUF =  ex.idUF AND uf.UFRegiao LIKE '" . $regiao . "'"
                    . " JOIN Atuacao AS atc ON atc.idAtuacao =  ex.idAtuacao AND atc.idAtuacao = " . $atc
                    . " JOIN Porte AS prt ON prt.idPorte =  ex.idPorte AND prt.idPorte = " . $prt
                    . " WHERE ex.Numero = " . $var;

                $query .= ($anos > 0) ? " AND ex.Ano = " . $anos : "";

                $result = mysqli_query(self::$conn, $query);
                $allObjects = array();

                while($obj = mysqli_fetch_object($result, 'EixoUm')){
                    $allObjects[] = $obj;
                }
                $result_aux = array();
                $value_aux = array();
                $percent_aux = array();
                foreach ($allObjects as $data) {
                    if(!isset($value_aux[$data->idUF])) $value_aux[$data->idUF] = 0;
                    if(!isset($percent_aux[$data->idUF])) $percent_aux[$data->idUF] = 0;
                    $value_aux[$data->idUF] += $data->Valor;
                    $percent_aux[$data->idUF] += $data->Percentual;
                    $result_aux[$data->idUF] = $data;
                    $result_aux[$data->idUF]->Valor = $value_aux[$data->idUF];
                    $result_aux[$data->idUF]->Percentual = $percent_aux[$data->idUF];
                }
                $allObjects = $result_aux;
            }

			while($obj = mysqli_fetch_object($result, 'EixoUm')){
				$allObjects[] = $obj;
			}

		self::disconnect();

        return $allObjects;
	}

}

?>