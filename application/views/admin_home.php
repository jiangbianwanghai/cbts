<?php include('common_header.php');?>

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> 我的控制台 <span>了解一些统计数据，全面掌握提测状态</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">我的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">巧克力提测系统</a></li>
          <li class="active">我的控制台</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">
      <div class="row">

        <div class="col-sm-6 col-md-6">
          <div class="panel panel-default panel-alt widget-messaging">
          <div class="panel-heading">
              <h3 class="panel-title">最新提测列表</h3>
            </div>
            <div class="panel-body">
              <ul>
                <?php
                  if ($testTop10) {
                    foreach ($testTop10 as $value) {
                ?>
                <li>
                  <small class="pull-right">提测人：<?php echo $value['add_user'] ? '@'.$users[$value['add_user']]['realname'] : '-';?></small>
                  <h4 class="sender"><a href="/issue/view/<?php echo $value['issue_id'];?>"><?php echo $repos[$value['repos_id']]['repos_name'];?> #<?php echo $value['test_flag'];?></a></h4>
                  <small>
                    <div class="mb10"></div>
                    <?php if ($value['rank'] == 0) {?>
                    <button class="btn btn-default btn-xs">开发环境</button>
                    <?php } ?>
                    <?php if ($value['rank'] == 1) {?>
                    <button class="btn btn-primary btn-xs">测试环境</button>
                    <?php } ?>
                    <?php if ($value['rank'] == 2) {?>
                    <button class="btn btn-success btn-xs">生产环境</button>
                    <?php } ?>

                    <?php if ($value['state'] == 0) {?>
                    <button class="btn btn-default btn-xs">待测</button>
                    <?php } ?>
                    <?php if ($value['state'] == 1) {?>
                    <button class="btn btn-primary btn-xs">测试中……</button>
                    <?php } ?>
                    <?php if ($value['state'] == -3) {?>
                    <button class="btn btn-danger btn-xs">不通过</button>
                    <?php } ?>
                    <?php if ($value['state'] == 3) {?>
                    <button class="btn btn-success btn-xs">通过</button>
                    <?php } ?>
                  </small>
                </li>
                <?php
                    }
                  }
                ?>
              </ul>
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->

        <div class="col-sm-6 col-md-6">
          <div class="panel panel-default panel-alt widget-messaging">
          <div class="panel-heading">
              <h3 class="panel-title">最新任务列表</h3>
            </div>
            <div class="panel-body">
              <ul>
                <?php
                  if ($testTop10) {
                    foreach ($issueTop10 as $value) {
                ?>
                <li>
                  <small class="pull-right">添加人：<?php echo $value['add_user'] ? '@'.$users[$value['add_user']]['realname'] : '-';?></small>
                  <h4 class="sender"><a href="/issue/view/<?php echo $value['id'];?>"><?php echo $value['issue_name'];?></a></h4>
                  <small>
                    <div class="mb10"></div>
                    <?php if ($value['resolve']) { ?> <span class="label label-success">已解决</span><?php } else {?> <span class="label label-info">未解决</span><?php } ?>
                    <?php if ($value['status'] == 1) {?> <span class="label label-primary">正常</span><?php }?>
                    <?php if ($value['status'] == 0) {?> <span class="label label-default">已关闭</span><?php }?>
                    <?php if ($value['status'] == -1) {?> <span class="label label-white">已删除</span><?php }?>
                  </small>
                </li>
                <?php
                    }
                  }
                ?>
              </ul>
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->

      </div>
    </div><!-- contentpanel -->

  </div><!-- mainpanel -->

</section>

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/jquery-ui-1.10.3.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/retina.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>
<script src="/static/js/custom.js"></script>
</body>
</html>
