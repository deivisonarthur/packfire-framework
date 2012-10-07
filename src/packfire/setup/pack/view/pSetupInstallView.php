<?php
pload('packfire.view.pView');

/**
 * pSetupInstallView Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.setup.view
 * @since 1.0-sofia
 */
class pSetupInstallView extends pView {

    protected function create() {
        $file = dirname(__FILE__) . '/../template/install.html';
        $this->template(new pMoustacheTemplate(file_get_contents($file)));
        $this->define('version', __PACKFIRE_VERSION__);
        $this->define('root', __PACKFIRE_ROOT__);
        
        if($this->state->get('complete')){
            $this->define('complete', true);
            $this->define('root', $this->state->get('root'));
        }
        if($this->state->get('fail')){
            $this->define('fail', true);
        }
    }
    
}