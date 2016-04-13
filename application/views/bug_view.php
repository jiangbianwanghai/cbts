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
              <li class="active"><a href="/bug"><i class="glyphicon glyphicon-inbox"></i> Bug列表</a></li>
              <li><a href="#"><i class="glyphicon glyphicon-star"></i> 星标</a></li>
              <li><a href="#"><i class="glyphicon glyphicon-trash"></i> 已删除</a></li>
          </ul>
          <div class="mb30"></div>
          
          <h5 class="subtitle">快捷方式</h5>
          <ul class="nav nav-pills nav-stacked nav-email mb20">
            <li><a href="#"><i class="glyphicon glyphicon-folder-open"></i> 我负责的</a></li>
            <li><a href="#"><i class="glyphicon glyphicon-folder-open"></i> 我创建的</a></li>
            <li><a href="#"><i class="glyphicon glyphicon-folder-open"></i> 我参与的</a></li>
            <li><a href="#"><i class="glyphicon glyphicon-folder-open"></i> 已完成的</a></li>
          </ul>
        </div><!-- col-sm-3 -->
            
            <div class="col-sm-9 col-lg-10">
                
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="pull-right">
                            <div class="btn-group mr10">
                                <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="Archive"><i class="glyphicon glyphicon-hdd"></i></button>
                                <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="Report Spam"><i class="glyphicon glyphicon-exclamation-sign"></i></button>
                                <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>
                            </div>
                            
                            <div class="btn-group mr5">
                                <button class="btn btn-sm btn-white" type="button"><i class="fa fa-reply"></i></button>
                                <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle" type="button">
                                  <span class="caret"></span>
                                </button>
                                <ul role="menu" class="dropdown-menu pull-right">
                                  <li><a href="#">Reply to All</a></li>
                                  <li><a href="#">Forward</a></li>
                                  <li><a href="#">Print</a></li>
                                  <li><a href="#">Delete Message</a></li>
                                  <li><a href="#">Report Spam</a></li>
                                </ul>
                            </div>
                            
                        </div><!-- pull-right -->
                        
                        <div class="btn-group mr10">
                            <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="Read Previous Email"><i class="glyphicon glyphicon-chevron-left"></i></button>
                            <button class="btn btn-sm btn-white tooltips" type="button" data-toggle="tooltip" title="Read Next Email"><i class="glyphicon glyphicon-chevron-right"></i></button>
                        </div>
                        
                        <div class="read-panel">
                            
                            <div class="media">
                                <div class="pull-left">
                                    <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$row['add_user']]['username']?>.jpg" align="absmiddle" title="<?php echo $users[$row['add_user']]['realname'];?>"></div>
                                </div>
                                <div class="media-body">
                                    <span class="media-meta pull-right"><?php echo friendlydate($row['add_time']);?></span>
                                    <h4 class="text-primary"><?php echo $users[$row['add_user']]['realname'];?></h4>
                                    <small class="text-muted"><?php echo $users[$row['add_user']]['email'];?></small>
                                </div>
                            </div><!-- media -->
                            
                            <h4 class="email-subject"><?php echo $row['subject'];?></h4>
                            
                            <p><?php echo $row['content'];?></p>
                            
                            <br />
                            
                            <div class="media">
                                <div class="pull-left">
                                  <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$this->input->cookie('uids')]['username']?>.jpg" align="absmiddle" title="<?php echo $users[$this->input->cookie('uids')]['realname'];?>"></div>
                                </div>
                                <div class="media-body">
                                    <textarea class="form-control" placeholder="Reply here..."></textarea>
                                </div>
                            </div><!-- media -->
                        
                        </div><!-- read-panel -->
                        
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
