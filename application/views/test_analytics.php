<?php include('common_header.php');?>

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> 提测统计 <span>了解一些统计数据，全面掌握提测状态</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">我的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">巧克力提测系统</a></li>
          <li><a href="/test/plaza">提测管理</a></li>
          <li class="active">提测统计</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">
      <?php if ($pie) { ?>
      <div class="row">
        <div class="col-md-6">
          <div class="panel panel-dark panel-alt">
            <div class="panel-heading">
              <div class="panel-btns">
                  <a href="" class="panel-close">&times;</a>
                  <a href="" class="minimize">&minus;</a>
              </div><!-- panel-btns -->
              <h5 class="panel-title">提测量排行</h5>
            </div><!-- panel-heading -->
            <div class="panel-body panel-table">
              <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr class="table-head-alt">
                      <th width="70">名次</th>
                      <th width="70">姓名</th>
                      <th width="70">提测量</th>
                      <th>占比</th>
                      <th width="70"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($pie as $key => $value) { ?>
                  <tr>
                    <td><?php echo $key + 1;?></td>
                    <td><?php echo $users[$value['add_user']]['realname'];?></td>
                    <td><?php echo $value['num'];?></td>
                    <td>
                      <div class="progress">
                          <div style="width: <?php echo sprintf("%.2f", $value['num']/$all_tice)*100;?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="15" role="progressbar" class="progress-bar progress-bar-warning">
                            <span class="sr-only"><?php echo sprintf("%.2f", $value['num']/$all_tice)*100;?> %</span>
                          </div>
                      </div>
                    </td>
                    <td><?php echo sprintf("%.2f", $value['num']/$all_tice)*100;?> %</td>
                  </tr>
                  <?php }?>
                </tbody>
              </table>
              </div><!-- table-responsive -->
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-md-6 -->
        <?php  }?>
        
    </div><!-- row -->
            
    </div><!-- contentpanel -->

  </div><!-- mainpanel -->

</section>

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/retina.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/custom.js"></script>

</body>
</html>
