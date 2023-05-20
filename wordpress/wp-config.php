<?php

/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */

define ('WPLANG', 'pt_BR');
setlocale(LC_ALL, 'pt_BR');

define('WP_MEMORY_LIMIT', '512M');
define('FS_METHOD','direct');
define('AUTOMATIC_UPDATER_DISABLED', true);
//define( 'WP_AUTO_UPDATE_CORE', false );


$env = [];
if( file_exists( dirname(__FILE__) . '/.env.wp' ) && count( parse_ini_file( dirname(__FILE__) . '/.env.wp' ) ) > 0 ) {
    $env = parse_ini_file( dirname(__FILE__) . '/.env.wp' );
} else {
    echo "no env";
    exit;
}

define( 'DB_NAME', $env['DB_NAME'] );
define( 'DB_USER', $env['DB_USER'] );
define( 'DB_PASSWORD', $env['DB_PASSWORD'] );
define( 'DB_HOST', $env['DB_HOST'] );


/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8' );
/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define( 'DB_COLLATE', '' );

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '15_COhG1PQpa<6][O4IbKD|Il-PxS1U2{(%FJ9B-Wbo1};zrJnBCmDtV= |5&~aa');
define('SECURE_AUTH_KEY',  'rq|*~?SJSF=I^T*ILmMa[+7|2m!S&=PTHjinUa7GWTr+,L[Onl8[hZ:}a?S O-ot');
define('LOGGED_IN_KEY',    'b}CFNW.=farWD+DTOmv)Z5aOg0,I&Ga<0TAK-3BfH-:R2nuQ>F!#W87)(|uF$LXT');
define('NONCE_KEY',        '+1C9R_TR&_h~5i(hADF~7k&5lcET6P}xo[FCV0(%S-a]Unru76{O/jZ~_*6jfngt');
define('AUTH_SALT',        'M!KxrO4[q {xhpq[}+XLWl23UY=8_[.CIGz+abbe:&f_O/O~K]U|z/VW|j^vByFz');
define('SECURE_AUTH_SALT', '?nWBVVCJ668+,qZjE|o)~q30]YD rQ(Elw,!b6~=X&:mpP+zFIB[}B-l)XDH-3R*');
define('LOGGED_IN_SALT',   'Jmr(XYaaTcy^,Ed0O]o-t=ta6&uvVB9bG|c>,})tAPlwH{yTE1^1| dYDqe6GO>K');
define('NONCE_SALT',       'HHoGH^?+auB)??lzQ*-/I ~DX6}cJw@>J:@>R:aCE5B9%;y?OFr#+/W|5<}[Q{i@');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix  = $env['DB_PREFIX'];

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */


/////////////////////////////////////////////////////////////////////
// DEBUG
if ( !is_dir(realpath(dirname(__FILE__)) . '/wp-content/logs/') )
	mkdir( realpath(dirname(__FILE__)) . '/wp-content/logs/' );
$log_path = realpath(dirname(__FILE__)) . '/wp-content/logs/'.date('Y-m-d',strtotime(date('Y-m-d 00:00:00')." - 1 day")).'.log';
$log_path_1sem = realpath(dirname(__FILE__)) . '/wp-content/logs/'.date('Y-m-d',strtotime(date('Y-m-d 00:00:00')." - 8 days")).'.log';
$log_path_2sem = realpath(dirname(__FILE__)) . '/wp-content/logs/'.date('Y-m-d',strtotime(date('Y-m-d 00:00:00')." - 15 days")).'.log';

if (!is_file($log_path)) {
	$file = fopen($log_path, 'w') or die("can't create log");
	fclose($file);
}
if (is_file($log_path_1sem)) { @unlink($log_path_1mes); }
if (is_file($log_path_2sem)) { @unlink($log_path_1mes); }

error_reporting(E_ERROR);
define('WP_DEBUG', (int)$env['DEBUG']);
define('WP_DEBUG_DISPLAY', (int)$env['DEBUG']);

@ini_set('log_errors', true);
@ini_set('display_errors', (int)$env['DEBUG']);
@ini_set('error_log',$log_path);

if(!function_exists('_log')){
	function _log( $message ) {
		if( is_array( $message ) || is_object( $message ) ){
			error_log( 'uid: ' . get_current_user_id() . ' -> ' . print_r( $message, true ) );
		} else {
			error_log( $message );
		}
	}
}


if ( defined( 'WP_CLI' ) ) {
    $_SERVER['HTTP_HOST'] = '127.0.0.1';
}


if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' )
    $protocol = 'https';
else
    $protocol = 'http';

define( 'WP_HOME', $protocol . '://' . $_SERVER['HTTP_HOST'] . $env['WP_PASTA'] );
define( 'WP_SITEURL', $protocol . '://' . $_SERVER['HTTP_HOST'] . $env['WP_PASTA'] );
define( 'WP_AUTO_UPDATE_CORE', false );




/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Configura as variáveis e arquivos do WordPress. */
require_once( ABSPATH . 'wp-settings.php' );
//Disable File Edits
define('DISALLOW_FILE_EDIT', true);