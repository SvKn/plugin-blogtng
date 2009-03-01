<?php
/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Michael Klier <chi@chimeric.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once(DOKU_PLUGIN.'action.php');

class action_plugin_blogtng_comments extends DokuWiki_Action_Plugin{

    var $commenthelper = null;

    function action_plugin_blogtng_comments() {
        $this->commenthelper =& plugin_load('helper', 'blogtng_comments');
    }

    function getInfo() {
        return confToHash(dirname(__FILE__).'/../INFO');
    }

    function register(&$controller) {
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'handle_act_preprocess', array());
        $controller->register_hook('ACTION_SHOW_REDIRECT', 'BEFORE', $this, 'handle_show_redirect', array());
    }

    function handle_show_redirect(&$event, $param) {
        if($event->data['preact']['comment_submit']) {
            $event->preventDefault();
        }
    }

    function handle_act_preprocess(&$event, $param) {
        global $INFO;

        if(is_array($event->data) && isset($event->data['comment_submit'])) {

            $comment = array();
            $comment['source'] = $_REQUEST['blogtng']['comment_source'];
            $comment['name']   = ($INFO['userinfo']['name']) ? $INFO['userinfo']['name'] : $_REQUEST['blogtng']['name'];
            $comment['mail']   = ($INFO['userinfo']['mail']) ? $INFO['userinfo']['mail'] : $_REQUEST['blogtng']['mail']; 
            $comment['text']   = $_REQUEST['wikitext'];
            $comment['pid']    = $_REQUEST['pid'];

            // FIXME default comment moderation etc.

            if($_REQUEST['blogtng']['subscribe']) {
                // FIXME handle subscription send opt-in etc
            }
            $event->data = 'show';
            return false;
        } else {
            return true;
        }
    }
}
// vim:ts=4:sw=4:et:enc=utf-8: