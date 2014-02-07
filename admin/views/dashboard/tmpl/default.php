<?php
/**
 * @package      VipQuotes
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * VipQuotes is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
// no direct access
defined('_JEXEC') or die;
?>
<?php if(!empty( $this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>
    <div class="span8">

        <!--  Row 1 -->
        <div class="row-fluid dashboard-stats">
            <div class="span8">
                <h3 class="latest-quotes stats-icon-list">
                    <?php echo JText::_("COM_VIPQUOTES_LATEST_QUOTES");?>
                </h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo JText::_("COM_VIPQUOTES_QUOTE");?></th>
                            <th class="center nowrap hidden-phone" style="max-width: 100px;">
                                <?php echo JText::_("COM_VIPQUOTES_DATE");?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i = 0, $max = count($this->latestQuotes); $i < $max; $i++) {?>
                        <tr>
                            <td><?php echo $i + 1;?></td>
                            <td>
                                <a href="<?php echo JRoute::_("index.php?option=com_vipquotes&view=quotes&filter_search=id:".(int)$this->latestQuotes[$i]["id"]);?>" >
                                    <?php echo strip_tags($this->latestQuotes[$i]["quote"]); ?>
                                </a>
                            </td>
                            <td class="center hidden-phone" style="min-width: 150px;">
                                <?php echo JHtml::_('date', $this->latestQuotes[$i]["created"], JText::_('DATE_FORMAT_LC3'));?>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <div class="span4">
                <h3 class="basic-stats stats-icon-info">
                    <?php echo JText::_("COM_VIPQUOTES_BASIC_INFORMATION");?>
                </h3>
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th><?php echo JText::_("COM_VIPQUOTES_TOTAL_QUOTES");?></th>
                            <td><?php echo $this->totalQuotes; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo JText::_("COM_VIPQUOTES_TOTAL_AUTHORS");?></th>
                            <td><?php echo $this->totalAuthors; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /Row 1 -->

        <!--  Row 1 -->
        <div class="row-fluid dashboard-stats">
            <div class="span8">
                <h3 class="popular-quotes stats-icon-list">
                    <?php echo JText::_("COM_VIPQUOTES_POPULAR_QUOTES");?>
                </h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo JText::_("COM_VIPQUOTES_QUOTE");?></th>
                            <th class="center nowrap hidden-phone" style="max-width: 150px;">
                                <?php echo JText::_("COM_VIPQUOTES_HITS");?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i = 0, $max = count($this->popularQuotes); $i < $max; $i++) {?>
                        <tr>
                            <td><?php echo $i + 1;?></td>
                            <td>
                                <a href="<?php echo JRoute::_("index.php?option=com_vipquotes&view=quotes&filter_search=id:".(int)$this->popularQuotes[$i]["id"]);?>" >
                                    <?php echo strip_tags($this->popularQuotes[$i]["quote"]); ?>
                                </a>
                            </td>
                            <td class="center hidden-phone" style="min-width: 100px;">
                                <?php echo (int)$this->popularQuotes[$i]["hits"];?>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <div class="span4">
                <h3 class="popular-authors stats-icon-list">
                    <?php echo JText::_("COM_VIPQUOTES_POPULAR_AUTHORS");?>
                </h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo JText::_("COM_VIPQUOTES_AUTHOR");?></th>
                            <th class="center nowrap hidden-phone" style="max-width: 100px;">
                                <?php echo JText::_("COM_VIPQUOTES_HITS");?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i = 0, $max = count($this->popularAuthors); $i < $max; $i++) {?>
                        <tr>
                            <td><?php echo $i + 1;?></td>
                            <td>
                                <a href="<?php echo JRoute::_("index.php?option=com_vipquotes&view=authors&filter_search=id:".(int)$this->popularAuthors[$i]["id"]);?>" >
                                    <?php echo JHtmlString::truncate(strip_tags($this->popularAuthors[$i]["name"]), 64); ?>
                                </a>
                            </td>
                            <td class="center hidden-phone">
                                <?php echo (int)$this->popularAuthors[$i]["hits"];?>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /Row 1 -->
        
	</div>
	
	<div class="span4">
        <a href="http://itprism.com/free-joomla-extensions/others/quotes-collection-manager" target="_blank"><img src="../media/com_vipquotes/images/logo.png" alt="<?php echo JText::_("COM_VIPQUOTES");?>" /></a>
        <a href="http://itprism.com" title="<?php echo JText::_("COM_VIPQUOTES_ITPRISM_PRODUCT");?>" target="_blank"><img src="../media/com_vipquotes/images/product_of_itprism.png" alt="<?php echo JText::_("COM_VIPQUOTES_ITPRISM_PRODUCT");?>" /></a>
        <p><?php echo JText::_("COM_VIPQUOTES_YOUR_VOTE"); ?></p>
        <p><?php echo JText::_("COM_VIPQUOTES_SPONSORSHIP"); ?></p>
        <p><?php echo JText::_("COM_VIPQUOTES_SUBSCRIPTION"); ?></p>
        
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td><?php echo JText::_("COM_VIPQUOTES_INSTALLED_VERSION");?></td>
                    <td><?php echo $this->version->getMediumVersion();?></td>
                </tr>
                <tr>
                    <td><?php echo JText::_("COM_VIPQUOTES_RELEASE_DATE");?></td>
                    <td><?php echo $this->version->releaseDate?></td>
                </tr>
                <tr>
                    <td><?php echo JText::_("COM_VIPQUOTES_ITPRISM_LIBRARY");?></td>
                    <td><?php echo $this->itprismVersion;?></td>
                </tr>
                <tr>
                    <td><?php echo JText::_("COM_VIPQUOTES_COPYRIGHT");?></td>
                    <td><?php echo $this->version->copyright;?></td>
                </tr>
                <tr>
                    <td><?php echo JText::_("COM_VIPQUOTES_LICENSE");?></td>
                    <td><?php echo $this->version->license;?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>