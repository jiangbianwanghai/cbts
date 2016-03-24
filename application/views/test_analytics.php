<?php include('common_header.php');?>

    <link rel="stylesheet" href="/static/css/daterangepicker.min.css" />

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
      <div class="row">
        <div class="col-md-12" ng-controller="TestCtrl">
          <form name="dateForm" class="form-inline" method="GET" action="/test/analytics">
            <div class="form-group">
              <label for="picker">时间范围：</label>
              <input date-range-picker id="picker" name="picker" class="form-control date-picker" style="width:200px;" type="text" min="'2016-02-16'" max="'<?php echo date("Y-m-d", strtotime("+1 day"));?>'"
                   ng-model="date" options="opts" required/>
            </div>
            <button type="submit" class="btn btn-primary">筛选</button>
          </form>
        </div>
      </div>

      <div class="mb20"></div>
      
      <div class="row">
        
        <div class="col-md-6">
          <div class="panel panel-dark panel-alt">
            <div class="panel-heading">
              <div class="panel-btns">
                  <a href="" class="panel-close">&times;</a>
                  <a href="" class="minimize">&minus;</a>
              </div><!-- panel-btns -->
              <h5 class="panel-title">提测量人员排行</h5>
            </div><!-- panel-heading -->
            <div class="panel-body panel-table">
              <?php if ($rankByUsers) { ?>
              <div class="table-responsive">
              <table class="table table-hover">
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
                  <?php foreach ($rankByUsers as $key => $value) { ?>
                  <tr>
                    <td><?php echo $key + 1;?></td>
                    <td><?php echo $value['add_user'] ? '<a href="/conf/profile/'.$value['add_user'].'">'.$users[$value['add_user']]['realname'].'</a>' : '-';?></td>
                    <td><?php echo $value['num'];?></td>
                    <td>
                      <div class="progress">
                          <div style="width: <?php echo sprintf("%.2f", $value['num']/$rankByUsersTotalNum)*100;?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="15" role="progressbar" class="progress-bar progress-bar-warning">
                            <span class="sr-only"><?php echo sprintf("%.2f", $value['num']/$rankByUsersTotalNum)*100;?> %</span>
                          </div>
                      </div>
                    </td>
                    <td><?php echo sprintf("%.2f", $value['num']/$rankByUsersTotalNum)*100;?> %</td>
                  </tr>
                  <?php }?>
                </tbody>
              </table>
              </div><!-- table-responsive -->
              <?php  } else {echo '暂无数据';}?>
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-md-6 -->
        

        
        <div class="col-md-6">
          <div class="panel panel-dark panel-alt">
            <div class="panel-heading">
              <div class="panel-btns">
                  <a href="" class="panel-close">&times;</a>
                  <a href="" class="minimize">&minus;</a>
              </div><!-- panel-btns -->
              <h5 class="panel-title">提测量代码库排行</h5>
            </div><!-- panel-heading -->
            <div class="panel-body panel-table">
              <?php if ($rankByRepos) { ?>
              <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr class="table-head-alt">
                      <th width="70">名次</th>
                      <th width="70">代码库</th>
                      <th width="70">提测量</th>
                      <th>占比</th>
                      <th width="70"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rankByRepos as $key => $value) { ?>
                  <tr>
                    <td><?php echo $key + 1;?></td>
                    <td><?php echo '<a href="/test/repos/'.$value['repos_id'].'">'.$repos[$value['repos_id']]['repos_name'].'</a>';?></td>
                    <td><?php echo $value['num'];?></td>
                    <td>
                      <div class="progress">
                          <div style="width: <?php echo sprintf("%.2f", $value['num']/$rankByReposTotalNum)*100;?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="15" role="progressbar" class="progress-bar progress-bar-warning">
                            <span class="sr-only"><?php echo sprintf("%.2f", $value['num']/$rankByReposTotalNum)*100;?> %</span>
                          </div>
                      </div>
                    </td>
                    <td><?php echo sprintf("%.2f", $value['num']/$rankByReposTotalNum)*100;?> %</td>
                  </tr>
                  <?php }?>
                </tbody>
              </table>
              </div><!-- table-responsive -->
              <?php  } else {echo '暂无数据';}?>
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-md-6 -->
        
        
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

<script src="/static/js/angular.min.js"></script>
<script src="/static/js/angular-messages.min.js"></script>
<script src="/static/js/moment.min.js"></script>
<script src="/static/js/daterangepicker.min.js"></script>
<script src="/static/js/angular-daterangepicker.js"></script>

<script>
  exampleApp = angular.module('example', ['ngMessages', 'daterangepicker']);
  exampleApp.controller('TestCtrl', function($scope) {
    $scope.date = {
        startDate: moment().subtract(<?php if ($leftTime == strtotime(date("Y-m-d", strtotime("-1 day"))) && $rightTime == strtotime(date("Y-m-d", time()))) { echo 1;} else {echo $day;}?>, "days"),
        <?php if ($leftTime == strtotime(date("Y-m-d", strtotime("-1 day"))) && $rightTime == strtotime(date("Y-m-d", time()))) {?>
        endDate: moment()
        <?php } else {?>
        endDate: moment().subtract(-1, 'days')
        <?php } ?>
    };

    $scope.opts = {
        locale: {
            applyClass: 'btn-green',
            applyLabel: "确定",
            cancelLabel: '取消',
            customRangeLabel: '自定义',
            daysOfWeek: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
            firstDay: 0,
            monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月',
                '十月', '十一月', '十二月'
            ]
        },
        ranges: {
          '今天': [moment(), moment().subtract(-1, 'days')],
          '昨天': [moment().subtract(1, 'days'), moment()],
          '最近一周': [moment().subtract(6, 'days'), moment().subtract(-1, 'days')],
          '最近一个月': [moment().subtract(29, 'days'), moment().subtract(-1, 'days')]
        }
    };
  });
  angular.bootstrap(document, ['example']);
</script>

</body>
</html>
