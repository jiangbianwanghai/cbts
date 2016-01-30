<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 提测管理 <span>任务详情</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/tice/task_list">提测管理</a></li>
          <li class="active">任务详情</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
                    
      <div class="panel">
          <div class="panel-heading">
              <h5 class="bug-key-title">ISSUE-<?php echo $row['id'];?></h5>
              <div class="panel-title"><?php echo $row['issue_name'];?></div>
          </div><!-- panel-heading-->
          <div class="panel-body">
              <div class="btn-group mr10">
                  <button class="btn btn-primary" type="button"><i class="fa fa-pencil mr5"></i> 编辑</button>
                  <button class="btn btn-primary" type="button"><i class="fa fa-comments mr5"></i> 提交代码</button>
                  <button class="btn btn-primary" type="button"><i class="fa fa-trash-o mr5"></i> 删除</button>
              </div>
              
              <div class="btn-group mr10">
                  <button class="btn btn-default" type="button">解决</button>
                  <button class="btn btn-default" type="button">关闭</button>
              </div>

              <br /><br />
              
              <div class="row">
                  <div class="col-sm-12">
                      
                      <h5 class="subtitle subtitle-lined">描述</h5>
                      <p><?php echo $row['issue_summary'];?></p>
                      
                      <div class="panel panel-dark panel-alt">
                        <div class="panel-heading">
                            <div class="panel-btns">
                                <a href="" class="panel-close">&times;</a>
                                <a href="" class="minimize">&minus;</a>
                            </div><!-- panel-btns -->
                            <h5 class="panel-title">提测记录</h5>
                        </div><!-- panel-heading -->
                        <div class="panel-body panel-table">
                            <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="table-head-alt">
                                      <th>#</th>
                                      <th>相关代码库</th>
                                      <th>提测版本标识</th>
                                      <th>提交时间</th>
                                      <th>受理进度</th>
                                      <th>阶段/状态</th>
                                      <th>添加时间</th>
                                      <th>添加人</th>
                                      <th>最后修改时间</th>
                                      <th>修改人</th>
                                      <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      if ($test) {
                                        foreach ($test as $value) {
                                    ?>
                                    <tr id="tr-<?php echo $value['id'];?>">
                                      <td><?php echo $value['id'];?></td>
                                      <td><?php echo $repos[$value['repos_id']]['repos_name'];?></td>
                                      <td><?php echo $value['test_flag'];?></td>
                                      <td><?php echo date("Y-m-d H:i:s", $value['add_time']);?></td>
                                      <td>
                                          <div class="progress">
                                              <div style="width: 2%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="2" role="progressbar" class="progress-bar progress-bar-danger">
                                                <span class="sr-only">2% Complete (success)</span>
                                              </div>
                                          </div>
                                      </td>
                                      <td>开发环境/未提测</td>
                                      <td><?php echo $value['add_time'] ? date("Y-m-d H:i:s", $value['last_time']) : '-';?></td>
                                      <td><?php echo $value['add_user'] ? $users[$value['add_user']]['realname'] : '-';?></td>
                                      <td><?php echo $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : '-';?></td>
                                      <td><?php echo $value['last_user'] ? $users[$value['last_user']]['realname'] : '-';?></td>
                                      <td class="table-action-hide">
                                        <a href="/test/add/<?php echo $value['id'];?>"><i class="fa fa-pencil"></i> 提测</a>
                                        <a href="/issue/edit/<?php echo $value['id'];?>"><i class="fa fa-pencil"></i> 编辑</a>
                                        <a href="javascript:;" class="delete-row" reposid="<?php echo $value['id'];?>"><i class="fa fa-trash-o"></i> 删除</a>
                                      </td>
                                    </tr>
                                    <?php
                                        }
                                      }
                                    ?>
                                </tbody>
                            </table>
                            </div><!-- table-responsive -->
                        </div><!-- panel-body -->
                    </div><!-- panel -->
                      
                  </div>
              </div><!-- row -->
              
          </div><!-- panel-body -->
      </div><!-- panel -->
      
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

<script src="/static/js/jquery.datatables.min.js"></script>
<script src="/static/js/select2.min.js"></script>

<script src="/static/js/custom.js"></script>
<script>
  $(document).ready(function(){
    $(".delete-row").click(function(){
      var c = confirm("确认要删除吗？");
      if(c) {
        id = $(this).attr("reposid");
        $.ajax({
          type: "GET",
          url: "/conf/task_del/"+id,
          dataType: "JSON",
          success: function(data){
            if (data.status) {
              $("#tr-"+id).fadeOut(function(){
                $("#tr-"+id).remove();
              });
              return false;
            } else {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-danger',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
              setTimeout(function(){
                location.href = data.url;
              }, 2000);
            };
          }
        });
      }
    });

    // Show aciton upon row hover
    jQuery('.table-responsive tbody tr').hover(function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 1});
    },function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 0});
    });

  });
</script>

</body>
</html>
