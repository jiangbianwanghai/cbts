<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-bug"></i> Bug管理 <span>我的Bug列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/bug">Bug管理</a></li>
          <li class="active">我的Bug列表</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel panel-email">
      <div class="row">
            <div class="col-sm-3 col-lg-2">
                
                <ul class="nav nav-pills nav-stacked nav-email">
                    <li class="active"><a href="#"><i class="glyphicon glyphicon-inbox"></i> 我的Bug</a></li>
                    <li><a href="#"><i class="glyphicon glyphicon-star"></i> 星标</a></li>
                    <li><a href="#"><i class="glyphicon glyphicon-trash"></i> 已删除</a></li>
                </ul>
              </ul>
                
            </div><!-- col-sm-3 -->
            
            <div class="col-sm-9 col-lg-10">
                
                <div class="panel panel-default">
                    <div class="panel-body">
                        
                        <div class="pull-right">
                          <div class="btn-group mr10">
                            <div class="btn-group nomargin">
                                <button data-toggle="dropdown" class="btn btn-sm btn-white" type="button">
                                  <i class="glyphicon glyphicon-folder-close mr5"></i> 根据状态筛选
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                  <li><a href="#"><i class="glyphicon glyphicon-folder-open mr5"></i> 未确认</a></li>
                                  <li><a href="#"><i class="glyphicon glyphicon-folder-open mr5"></i> 处理中</a></li>
                                  <li><a href="#"><i class="glyphicon glyphicon-folder-open mr5"></i> 已处理</a></li>
                                </ul>
                            </div>
                            <div class="btn-group nomargin">
                                <button data-toggle="dropdown" class="btn btn-sm btn-white" type="button">
                                  <i class="glyphicon glyphicon-flag mr5"></i> 根据项目筛选
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                  <li><a href="#"><i class="glyphicon glyphicon-flag mr5"></i> Forbuyers</a></li>
                                  <li><a href="#"><i class="glyphicon glyphicon-flag mr5"></i> 中文站-用户运营</a></li>
                                  <li><a href="#"><i class="glyphicon glyphicon-flag mr5"></i> 中文站-营销效果</a></li>
                                </ul>
                            </div>
                          </div>
                            <div class="btn-group mr10">
                                <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="Archive"><i class="glyphicon glyphicon-hdd"></i></button>
                                <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="Report Spam"><i class="glyphicon glyphicon-exclamation-sign"></i></button>
                                <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>
                            </div>
                        </div><!-- pull-right -->
                        
                        <h5 class="subtitle mb5">我收到的Bug</h5>
                        <p class="text-muted">查询结果：<?php echo ($offset+1).' - '.($offset+$per_page).' of '.$total;?></p>
                        
                        <div class="table-responsive">
                          <table class="table table-email">
                            <tbody>
                              <?php
                                if ($rows) {
                                  foreach ($rows as $value) {
                              ?>
                              <tr class="unread">
                                <td>
                                  <div class="ckbox ckbox-success">
                                      <input type="checkbox" id="checkbox1">
                                      <label for="checkbox1"></label>
                                  </div>
                                </td>
                                <td>
                                  <a href="" class="star"><i class="glyphicon glyphicon-star"></i></a>
                                </td>
                                <td width="70px;">
                                  <?php if ($value['state'] === '0') {?>
                                  <span class="label label-default">未确认</span>
                                  <?php } ?>
                                  <?php if ($value['state'] === '1') {?>
                                  <span class="label label-primary">处理中</span>
                                  <?php } ?>
                                  <?php if ($value['state'] === '3') {?>
                                  <span class="label label-success">已处理</span>
                                  <?php } ?>
                                </td>
                                <td>
                                  <div class="media">
                                      <a href="#" class="pull-left">
                                        <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['accept_user']]['username']?>.jpg" align="absmiddle" title="<?php echo $users[$value['accept_user']]['realname'];?>"></div>
                                      </a>
                                      <div class="media-body">
                                          <span class="media-meta pull-right"><?php echo friendlydate($value['add_time']);?></span>
                                          <p class="email-summary"><?php if ($value['level']) {?><?php echo "<strong style='color:#ff0000;' title='".$level[$value['level']]['alt']."'>".$level[$value['level']]['name']."</strong> ";?><?php } ?> <?php echo $value['subject'];?></p>
                                      </div>
                                  </div>
                                </td>
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
<script src="/static/js/retina.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.datatables.min.js"></script>
<script src="/static/js/select2.min.js"></script>

<script src="/static/js/custom.js"></script>

</body>
</html>
