<?php
  /**
   * Coupon-Type
   *
   * @package Wojo Framework
   * @author Lorenzo Mateo
   * @copyright 2017
   * @version $Id: Coupon.php, v1.00 2017-08-29 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!Auth::hasPrivileges('manage_coupontype')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php $data = $content->getCoupontype();?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->CPTYPE_SUB;?></div>
    <p><?php echo Lang::$word->CPTYPE_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->CPTYPE_TITLE;?></span> <a data-set='{"title": "<?php echo Lang::$word->DEAL_ADDNEW;?>", "option": "addNewCoupontype", "label": "<?php echo Lang::$word->CPTYPE_ADD;?>", "redirect": "<?php echo Url::adminUrl("coupontype");?>"}' id="addNew" class="wojo large top right detached action label" data-content="<?php echo Lang::$word->CPTYPE_ADD;?>"><i class="icon plus"></i></a> </div>
  <?php if(!$data):?>
  <?php echo Message::msgSingleAlert(Lang::$word->CPTYPE_NOCoupontype);?>
  <?php else:?>
  <ul class="wojo block grid divided large-3 medium-2 small-1" id="editable">
    <?php foreach($data as $row):?>
    <li class="item"> <a class="delete wojo top right corner label" data-set='{"title": "<?php echo Lang::$word->CPTYPE_DEL;?>", "parent": "li", "option": "deleteCoupontype", "id": <?php echo $row->id;?>, "name": "<?php echo $row->name;?>"}'><i class="icon negative delete link"></i></a>
      <div class="content" data-editable="true" data-set='{"type": "coupontype", "id": <?php echo $row->id;?>,"key":"name", "path":""}'><?php echo $row->name;?></div>
    </li>
    <?php endforeach;?>
  </ul>
  <?php endif;?>
</div>