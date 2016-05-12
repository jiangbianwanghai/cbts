<?php include('common_header.php');?>
<style type="text/css">
th,td{white-space:nowrap;}
</style>
    <div class="pageheader">
      <h2><i class="fa fa-suitcase"></i> 数据分析 <span>计划列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">CITS</a></li>
          <li><a href="/analytics">数据分析</a></li>
          <li class="active">计划列表</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
      <div class="row">
        <div class="col-sm-3 col-lg-2">
          <?php include('analytics_sider.php');?>
        </div><!-- col-sm-3 -->
        <div class="col-sm-9 col-lg-10">
          <div class="panel panel-default">
            <div class="panel-body">
              <h5 class="subtitle mb5">计划列表</h5>
              <?php if (($total-$offset) < $per_page) { $per_page_end = $total-$offset; } else { $per_page_end = $per_page; }?>
              <p class="text-muted">查询结果：<?php echo ($offset+1).' - '.($per_page_end+$offset).' of '.$total;?></p>
              <div class="table-responsive" style="overflow-x:auto;">
                <table class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>计划全称</th>
                      <th>所属项目团队</th>
                      <th>计划描述</th>
                      <th>进度</th>
                      <th>开始时间</th>
                      <th>结束时间</th>
                      <th>添加人</th>
                      <th>添加时间</th>
                      <th>最后修改人</th>
                      <th>修改时间</th>
                      <th>状态</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($rows) {
                      foreach ($rows as $key => $value) {
                    ?>
                    <tr>
                      <td><?php echo ($offset + $key + 1); ?></td>
                      <td><a href="/plan?planId=<?php echo $value['id']; ?>"><?php echo $value['plan_name']; ?></a></td>
                      <td><?php echo $projectArr[$value['project_id']]['project_name']; ?></td>
                      <td><a href="javascript:;">查看简介</a></td>
                      <td><?php echo '<span class="label label-'.$planflow[$value['state']]['span_color'].'">'.$planflow[$value['state']]['name'].'</span>'; ?></td>
                      <td><?php echo date('Y/m/d H:i', $value['startime']); ?></td>
                      <td><?php echo date('Y/m/d H:i', $value['endtime']); ?></td>
                      <td><?php echo $users[$value['add_user']]['realname']; ?></td>
                      <td><?php echo $value['add_time'] ? date('Y/m/d H:i:s', $value['add_time']) : '-'; ?></td>
                      <td><?php echo $value['last_user'] ? $users[$value['last_user']]['realname'] : '-'; ?></td>
                      <td><?php echo $value['last_time'] ? date('Y/m/d H:i:s', $value['last_time']) : '-'; ?></td>
                      <td><?php if ($value['status'] == 1) echo '正常'; ?><?php if ($value['status'] == -1) echo '已删除'; ?></td>
                    </tr>
                    <?php
                        }
                      } else {
                    ?>
                      <tr><td colspan="11" align="center">无数据~</td></tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div><!-- table-responsive -->
              <?php echo $pages;?>
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-md-9 -->
      </div><!--row -->
      <p class="text-right"><small>页面执行时间 <em>{elapsed_time}</em> 秒 使用内存 {memory_usage}</small></p>
    </div><!-- contentpanel -->
    
  </div><!-- mainpanel -->
  
</section>

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-ui-1.10.3.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/custom.js"></script>

</body>
</html>
