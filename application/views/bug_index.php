<?php include('common_header.php');?>
  <div class="pageheader">
    <h2><i class="fa fa-bug"></i> Bug管理 <span>当前项目的Bug列表</span></h2>
    <div class="breadcrumb-wrapper">
      <span class="label">你的位置:</span>
      <ol class="breadcrumb">
        <li><a href="/">我的控制台</a></li>
        <li><a href="/bug">Bug管理</a></li>
        <li class="active">当前项目的Bug列表</li>
      </ol>
    </div>
  </div>
  <div class="contentpanel panel-email">
    <div class="row">
      <div class="col-sm-3 col-lg-2">
        <ul class="nav nav-pills nav-stacked nav-email">
          <li<?php if ($this->uri->segment(1, 'index') == 'bug' && $this->uri->segment(2, 'index') == 'index' && $this->uri->segment(3, 'all') == 'all') {?> class="active"<?php } ?>><a href="/bug"><i class="glyphicon glyphicon-inbox"></i> Bug列表</a></li>
          <li<?php if ($this->uri->segment(2, '') == 'star') {?> class="active"<?php } ?>><a href="/bug/star"><i class="glyphicon glyphicon-star"></i> 星标</a></li>
          <li<?php if ($this->uri->segment(2, '') == 'trash') {?> class="active"<?php } ?>><a href="/bug/trash"><i class="glyphicon glyphicon-trash"></i> 已删除</a></li>
        </ul>
        <div class="mb30"></div>
        <h5 class="subtitle">快捷方式</h5>
        <ul class="nav nav-pills nav-stacked nav-email mb20">
          <li<?php if ($folder == 'to_me') { ?> class="active"<?php } ?>><a href="/bug/index/to_me"><i class="glyphicon glyphicon-folder-<?php echo $folder == 'to_me' ? 'open' : 'close'; ?>"></i> 我负责的</a></li>
          <li<?php if ($folder == 'from_me') { ?> class="active"<?php } ?>><a href="/bug/index/from_me"><i class="glyphicon glyphicon-folder-<?php echo $folder == 'from_me' ? 'open' : 'close'; ?>"></i> 我创建的</a></li>
        </ul>
      </div><!-- col-sm-3 -->
      <div class="col-sm-9 col-lg-10">
        <div class="panel panel-default">
          <div class="panel-body">
            <?php if ($this->uri->segment(2, 'index') == 'index') {?>
            <div class="pull-right">
              <div class="btn-group">
                <div class="btn-group nomargin">
                  <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="根据处理状态筛选">
                    <i class="glyphicon glyphicon-folder-close mr5"></i> <?php if ($state == 'all') { echo '处理状态筛选'; }?><?php if ($state == 'uncheck') { echo '未处理'; }?><?php if ($state == 'checkin') { echo '已确认'; }?><?php if ($state == 'doing') { echo '处理中'; }?><?php if ($state == 'over') { echo '已处理'; }?><?php if ($state == 'invalid') { echo '无效反馈'; }?>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <?php if ($state != 'all') {?>
                    <li><a href="/bug/index/<?php echo $folder;?>/all"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部状态</a></li>
                    <?php } ?>
                    <li><a href="/bug/index/<?php echo $folder;?>/uncheck"><i class="glyphicon glyphicon-folder-open mr5"></i> 未确认</a></li>
                    <li><a href="/bug/index/<?php echo $folder;?>/checkin"><i class="glyphicon glyphicon-folder-open mr5"></i> 已确认</a></li>
                    <li><a href="/bug/index/<?php echo $folder;?>/doing"><i class="glyphicon glyphicon-folder-open mr5"></i> 处理中</a></li>
                    <li><a href="/bug/index/<?php echo $folder;?>/over"><i class="glyphicon glyphicon-folder-open mr5"></i> 已处理</a></li>
                    <li><a href="/bug/index/<?php echo $folder;?>/invalid"><i class="glyphicon glyphicon-folder-open mr5"></i> 无效反馈</a></li>
                  </ul>
                </div>
                <div class="btn-group nomargin">
                  <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="根据信息状态筛选">
                    <i class="glyphicon glyphicon-tag mr5"></i> <?php if ($status == 'all') { echo '信息状态筛选'; }?><?php if ($status == 'normal') { echo '正常'; }?><?php if ($status == 'close') { echo '关闭'; }?><?php if ($status == 'del') { echo '已删除'; }?>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <?php if ($status != 'all') {?>
                    <li><a href="/bug/index/<?php echo $folder;?>/<?php echo $state;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部状态</a></li>
                    <?php } ?>
                    <li><a href="/bug/index/<?php echo $folder;?>/<?php echo $state;?>/normal"><i class="glyphicon glyphicon-tag mr5"></i> 正常</a></li>
                    <li><a href="/bug/index/<?php echo $folder;?>/<?php echo $state;?>/close"><i class="glyphicon glyphicon-tag mr5"></i> 关闭</a></li>
                    <li><a href="/bug/index/<?php echo $folder;?>/<?php echo $state;?>/del"><i class="glyphicon glyphicon-tag mr5"></i> 已删除</a></li>
                  </ul>
                </div>
              </div>
            </div><!-- pull-right -->
            <?php } ?>
            <h5 class="subtitle mb5">Bug列表</h5>
            <?php if (($total-$offset) < $per_page) { $per_page_end = $total-$offset; } else { $per_page_end = $per_page; }?>
            <p class="text-muted">查询结果：<?php echo ($offset+1).' - '.($per_page_end+$offset).' of '.$total;?></p>
            <div class="table-responsive">
              <table class="table table-email">
                <tbody>
                  <?php
                    if ($rows) {
                      $weekarray=array("日","一","二","三","四","五","六");
                      if (file_exists('./cache/users.conf.php'))
                          require './cache/users.conf.php';
                      foreach ($rows as $value) {
                        $timeDay = date("Ymd", $value['add_time']);
                        if (!isset($timeGroup[$timeDay])) {
                          if ($timeDay == date("Ymd", time())) {
                            $day = '<span style="color:green">今天</span>';
                          } else {
                            $day = date('Y-m-d', $value['add_time']).' 星期'.$weekarray[date("w",$value['add_time'])];
                          }
                          echo '<tr><td colspan="7"><span class="fa fa-calendar"></span> 创建时间：'.$day.'</td></tr>';
                        }
                        $timeGroup[$timeDay] = 1;
                  ?>
                  <tr class="unread">
                    <td>
                      <div class="ckbox ckbox-success">
                          <input type="checkbox" id="checkbox<?php echo $value['id'];?>">
                          <label for="checkbox<?php echo $value['id'];?>"></label>
                      </div>
                    </td>
                    <td>
                      <a href="javascript:;" bugid="<?php echo $value['id'];?>" class="star<?php if ($this->uri->segment(2, '') == 'star') { echo ' star-checked'; } else { if (isset($star[$value['id']])) echo ' star-checked'; }?>"><i class="glyphicon glyphicon-star"></i></a>
                    </td>
                    <td width="50px">
                      <a href="/conf/profile/<?php echo $value['add_user'];?>" class="pull-left" target="_blank">
                        <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['add_user']]['username']?>.jpg" align="absmiddle" title="反馈人：<?php echo $users[$value['add_user']]['realname'];?>"></div>
                      </a>
                    </td>
                    <td width="40px">
                      <a href="#" class="pull-left">
                        <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['accept_user']]['username']?>.jpg" align="absmiddle" title="处理人：<?php echo $users[$value['accept_user']]['realname'];?>"></div>
                      </a>
                    </td>
                    <td width="50px">
                      <?php
                      if ($value['status'] == 1) {
                        echo '<span class="label label-info">开启</span>';
                      } elseif ($value['status'] == 0) {
                        echo '<span class="label label-default">关闭</span>';
                      }elseif ($value['status'] == '-1') {
                        echo '<span class="label label-default">删除</span>';
                      }
                      ?>
                    </td>
                    <td width="70px">
                      <?php if ($value['state'] === '-1') {?>
                      <span class="label label-default">无效反馈</span>
                      <?php } ?>
                      <?php if ($value['state'] === '0') {?>
                      <span class="label label-default">未确认</span>
                      <?php } ?>
                      <?php if ($value['state'] === '1') {?>
                      <span class="label label-primary">已确认</span>
                      <?php } ?>
                      <?php if ($value['state'] === '2') {?>
                      <span class="label label-warning">处理中</span>
                      <?php } ?>
                      <?php if ($value['state'] === '3') {?>
                      <span class="label label-info">已处理</span>
                      <?php } ?>
                      <?php if ($value['state'] === '5') {?>
                      <span class="label label-success">通过回归</span>
                      <?php } ?>
                    </td>
                    <td>
                      <?php if ($value['level']) {?><?php echo "<strong style='color:#ff0000;' title='".$level[$value['level']]['alt']."'>".$level[$value['level']]['name']."</strong> ";?><?php } ?> <a href="/bug/view/<?php echo $value['id'];?>"><?php echo $value['subject'];?></a>
                    </td>
                    <td><span class="media-meta pull-right"><?php echo date("Y/m/d H:i", $value['add_time'])?></span></td>
                  </tr>
                  <?php
                      }
                    } else {
                  ?>
                    <tr><td align="center">无数据~</td></tr>
                  <?php
                    }
                  ?>
                </tbody>
              </table>
              <?php echo $pages;?>
            </div><!-- table-responsive -->
          </div><!-- panel-body -->
        </div><!-- panel -->   
      </div><!-- col-sm-9 -->
    </div><!-- row -->
  </div><!-- contentpanel -->
</div><!-- mainpanel -->
<?php include('common_users.php');?>
</section>

<div class="modal fade bs-example-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title">提测详情</h4>
        </div>
        <div class="modal-body">...</div>
    </div>
  </div>
</div>

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.datatables.min.js"></script>
<script src="/static/js/select2.min.js"></script>

<script src="/static/js/custom.js"></script>
<script>
jQuery(document).ready(function(){
  
  "use strict"

  //Check
  jQuery('.ckbox input').click(function(){
      var t = jQuery(this);
      if(t.is(':checked')){
          t.closest('tr').addClass('selected');
      } else {
          t.closest('tr').removeClass('selected');
      }
  });
  
  // Star
  $('.star').click(function(){
      //id = $(this).attr("bugid");
      //alert(ids);
      if(!jQuery(this).hasClass('star-checked')) {
          jQuery(this).addClass('star-checked');
          var id = jQuery(this).attr('bugid');
          $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "/bug/star_ajax/"+id,
            success: function(data){
              if (data.status) {
                alert(data.message);
              } else {
                alert(data.message);
              } 
            }
          });
      } else {
        jQuery(this).removeClass('star-checked');
        var id = jQuery(this).attr('bugid');
        $.ajax({
          type: "GET",
          dataType: "JSON",
          url: "/bug/star_del/"+id,
          success: function(data){
            if (data.status) {
              alert(data.message);
            } else {
              alert(data.message);
            } 
          }
        });
      }
      return false;
  });

});
</script>

</body>
</html>
