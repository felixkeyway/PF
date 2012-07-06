<?php
// settings import
require_once './setup.inc.php';

/**
 * This file installs the Portal Framework and configure the database access.
 * 
 * @author          Felix Jacobi <jacobi@keyway-xelonis.com>
 * @license         GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl.html> 
 */

class InstallMain {
    /**
     * Calls the methods for archive unpacking and class file loading. 
     */
    
    public function __construct() {
        $this->unpackArchive();
        $_SESSION['language'] = 'de';
    }
    
    /**
     * Unpacks KPFSetup.tar.gz. 
     */
    
    protected function unpackArchive() {
        $_SESSION['' . $setupConfig['session']['variable_prefix'] . 'archiveIsUnpack'] = true;
    }
}

// creates a instance of InstallMain
$installMain = new InstallMain;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php $installMain->getSettings()->language(); ?>">
</html>