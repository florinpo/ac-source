<?php
/**
 * ExtGoogleAnalyticsCounter class file.
 * @author Zampogna Gianluca
 * @copyright Copyright &copy; Zampogna Gianluca 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * Widget to show statistics of your website using Google Analytics API
 */
/*
 *
 * Put the following code in params
 *

   'ga_email' => 'your@email.com'
   , 'ga_password' => 'yourpassword'
   , 'ga_profile_id' => 'profileid'


 * Put the following code in main.php
 *If you don't enter text in strTotalVisits or strDayVisits they don't appear.
 *
<?php $this->widget('ext.gaCounter.ExtGoogleAnalyticsCounter', array(
    'strTotalVisits' => 'Totale visite',
    'strDayVisits' => 'Visite giornaliere')
);?>

Charts:
<?php
    $this->widget('ext.gaCounter.ExtGoogleAnalyticsCounter', array(
        'lastYearChart' => true,
        'title' => 'Last year',
        'width' => 660
        )
    );
    $this->widget('ext.gaCounter.ExtGoogleAnalyticsCounter', array(
        'lastMonthChart' => true,
        'title' => 'Last month',
        'width' => 400
        )
    );
    $this->widget('ext.gaCounter.ExtGoogleAnalyticsCounter', array(
        'customDateChart' => true,
        'startDate' => date('Y-m-d', strtotime('-1 week')),
        'endDate' => date("Y-m-d"),
        'typeChart' => 'day',
        'title' => 'Last week')
    );
?>
*/

/*
 * If you have BadAuthentication error go to this url https://accounts.google.com/DisplayUnlockCaptcha and unlock.
*/
class gapi extends CWidget
{
   
}