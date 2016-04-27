<?php include('common_header.php');?>

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> 我的面板 <span>显示关于你的所有任务</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">我的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">CITS</a></li>
          <li class="active">我的面板</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">
      <div class="col-sm-3 col-lg-2">
        <h5 class="subtitle">快捷方式</h5>
        <ul class="nav nav-pills nav-stacked nav-email mb20">
          <li<?php if ($this->uri->segment(3, 'to_me') == 'to_me') {?> class="active"<?php } ?>><a href="/admin/index/to_me"><i class="glyphicon glyphicon-folder-<?php if ($this->uri->segment(3, 'to_me') == 'to_me') { echo 'open';} else { echo 'close';}?>"></i> 我负责的</a></li>
          <li<?php if ($this->uri->segment(3, '') == 'from_me') {?> class="active"<?php } ?>><a href="/admin/index/from_me"><i class="glyphicon glyphicon-folder-<?php if ($this->uri->segment(3, '') == 'from_me') { echo 'open';} else { echo 'close';}?>"></i> 我创建的</a></li>
          <li<?php if ($this->uri->segment(3, '') == 'over') {?> class="active"<?php } ?>><a href="/admin/index/over"><i class="glyphicon glyphicon-folder-<?php if ($this->uri->segment(3, '') == 'over') { echo 'open';} else { echo 'close';}?>"></i> 已完成的</a></li>
        </ul>
      </div><!-- col-sm-3 -->
      <div class="col-sm-9 col-lg-10">
        <div class="panel panel-default">
          <div class="panel-body">
            <h5 class="subtitle mb5"><?php if ($this->uri->segment(3, 'to_me') == 'to_me') { echo '我负责的'; }?><?php if ($this->uri->segment(3, '') == 'from_me') { echo '我创建的'; }?><?php if ($this->uri->segment(3, '') == 'over') { echo '已完成的'; }?></h5>
            <?php if (($total-$offset) < $per_page) { $per_page_end = $total-$offset; } else { $per_page_end = $per_page; }?>
            <p class="text-muted">查询结果：<?php echo ($offset+1).' - '.($per_page_end+$offset).' of '.$total;?></p>
            <div class="table-responsive">
              <table class="table table-email">
                <tbody>
                  <?php
                    if ($rows) {
                      if (file_exists('./cache/users.conf.php'))
                          require './cache/users.conf.php';
                      foreach ($rows as $value) {
                  ?>
                  <tr class="unread">
                    <td>
                      <div class="ckbox ckbox-success">
                        <input type="checkbox" id="checkbox<?php echo $value['id'];?>">
                        <label for="checkbox<?php echo $value['id'];?>"></label>
                      </div>
                    </td>
                    <td>
                      
                    </td>
                    <td width="80px">
                      处理进度
                    </td>
                    <td align="center" width="30px">
                      <?php if ($value['type'] == 2) {?><i class="fa fa-bug tooltips" data-toggle="tooltip" title="BUG"></i><?php } ?><?php if ($value['type'] == 1) {?><i class="fa fa-magic tooltips" data-toggle="tooltip" title="TASK"></i><?php } ?>
                    </td>
                    <td><?php if ($value['level']) {?><?php echo "<strong style='color:#ff0000;' title='".$level[$value['level']]['alt']."'>".$level[$value['level']]['name']."</strong> ";?><?php } ?> <a href="/issue/view/<?php echo $value['id'];?>" target="_blank"><?php echo $value['issue_name'];?></a></span></td>
                    <td align="center" width="40px">
                      <a href="/conf/profile/<?php echo $value['add_user'];?>" class="pull-left" target="_blank">
                        <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['add_user']]['username']?>.jpg" align="absmiddle" title="添加人：<?php echo $users[$value['add_user']]['realname'];?>"></div>
                      </a>
                    </td>
                    <td align="center" width="40px">
                      <?php if ($value['accept_user']) {?>
                      <a href="/conf/profile/<?php echo $value['accept_user'];?>" class="pull-left" target="_blank">
                        <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['accept_user']]['username']?>.jpg" align="absmiddle" title="当前受理人：<?php echo $users[$value['accept_user']]['realname'];?>"></div>
                      </a>
                      <?php } else { echo '-'; } ?>
                    </td>
                  </tr>
                  <?php
                      }
                    } else {
                  ?>
                    <tr><td colspan="5" align="center">无数据~</td></tr>
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
    </div><!-- contentpanel -->
  </div><!-- mainpanel -->
  <?php include('common_users.php');?>
</section>

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/jquery-ui-1.10.3.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.validate.min.js"></script>
<script src="/static/js/jquery.form.js"></script>
<script src="/static/js/jquery.gritter.min.js"></script>

<script src="/static/js/masonry.pkgd.min.js"></script>

<script src="/static/js/custom.js"></script>
<script src="/static/js/cits.js"></script>

</body>
</html>
