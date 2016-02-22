<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 任务管理 <span>任务详情</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/tice/task_list">任务管理</a></li>
          <li class="active">任务详情</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
      <?php if ($row['status'] == '-1') { ?>
      <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>抱歉~</strong> 该任务已被删除.
      </div>
      <?php } ?>              
      <div class="panel">
          <div class="panel-heading">
              <h5 class="bug-key-title">ISSUE-<?php echo $row['id'];?></h5>
              <div class="panel-title"><?php if ($row['status'] == '-1') { ?><s><?php echo $row['issue_name'];?></s><?php } else { ?><?php echo $row['issue_name'];?><?php } ?> <?php if ($row['resolve']) { ?> <span class="label label-success">已解决</span><?php }?> <?php if ($row['status'] == 0) {?> <span class="label label-default">已关闭</span><?php }?></div>
          </div><!-- panel-heading-->
          <div class="panel-body">
              <?php if ($row['status'] == 1 && $row['resolve'] == 0) { ?>
              <div class="btn-group mr10">
                  <a href="/issue/edit/<?php echo $row['id'];?>" class="btn btn-primary"><i class="fa fa-pencil mr5"></i> 编辑</a>
                  <a href="/test/add/<?php echo $row['id'];?>" class="btn btn-primary"><i class="fa fa-comments mr5"></i> 提交代码</a>
                  <a href="javascript:;" id="del" reposid="<?php echo $row['id'];?>" class="btn btn-primary"><i class="fa fa-trash-o mr5"></i> 删除</a>
              </div>
              
              <div class="btn-group mr10">
                  <a href="javascript:;" id="resolve" reposid="<?php echo $row['id'];?>" class="btn btn-default" type="button">解决</a>
                  <a href="javascript:;" id="close" reposid="<?php echo $row['id'];?>" class="btn btn-default" type="button">关闭</a>
              </div>

              <br /><br />
              <?php } ?>
              
              <div class="row">
                  <div class="col-sm-12">
                      <h5 class="subtitle subtitle-lined">信息</h5>
                      <div class="row">
                          <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-6">提交人</div>
                                <div class="col-xs-6"><?php echo $row['add_user'] ? '<a href="/conf/profile/'.$row['add_user'].'">'.$users[$row['add_user']]['realname'].'</a>' : '-';?></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">最后修改人</div>
                                <div class="col-xs-6"><?php echo $row['add_user'] ? '<a href="/conf/profile/'.$row['last_user'].'">'.$users[$row['last_user']]['realname'].'</a>' : '-';?></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">受理人</div>
                                <div class="col-xs-6"><?php echo $row['add_user'] ? '<a href="/conf/profile/'.$row['accept_user'].'">'.$users[$row['accept_user']]['realname'].'</a>' : '-';?></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">相关链接：</div>
                                <div class="col-xs-6"><?php if ($row['url']) { echo "<a href=\"".$row['url']."\" target=\"_blank\">点击查看</a>";}?></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">所属产品版本</div>
                                <div class="col-xs-6">-</div>
                            </div>
                          </div><!-- col-sm-6 -->
                          <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-6">提交时间</div>
                                <div class="col-xs-6"><?php echo $row['add_time'] ? date("Y-m-d H:i:s", $row['add_time']) : '-';?></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">最后修改时间</div>
                                <div class="col-xs-6"><?php echo $row['last_time'] ? date("Y-m-d H:i:s", $row['last_time']) : '-';?></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">受理时间：</div>
                                <div class="col-xs-6"><?php echo $row['accept_time'] ? date("Y-m-d H:i:s", $row['accept_time']) : '-';?></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">标签</div>
                                <div class="col-xs-6">-</div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">都谁贡献了代码</div>
                                <div class="col-xs-6">
                                  <?php 
                                  if ($shareUsers) {
                                    foreach($shareUsers as $val) {
                                      echo '<a href="/conf/profile/'.$val.'">'.$users[$val]['realname'].'</a> ';
                                  ?> 
                                  <?php 
                                    }
                                  }else { 
                                    echo '-';
                                  }
                                  ?>
                                </div>
                            </div>
                          </div><!-- col-sm-6 -->
                      </div><!-- row -->
                      
                      <br /><br />
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
                                      <th>所处阶段</th>
                                      <th>提测状态</th>
                                      <th>添加人</th>
                                      <th>最后修改人</th>
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
                                      <td><a href="/test/repos/<?php echo $value['repos_id'];?>"><?php echo $repos[$value['repos_id']]['repos_name'];?></a></td>
                                      <td><?php echo $value['test_flag'];?></td>
                                      <td>
                                        <?php if ($value['rank'] == 0) {?>
                                        <button class="btn btn-default btn-xs"><i class="fa fa-coffee"></i> 开发环境</button>
                                        <?php } ?>
                                        <?php if ($value['rank'] == 1) {?>
                                        <button class="btn btn-primary btn-xs"><?php if ($value['state'] == 5) { ?><i class="fa fa-exclamation-circle"></i> <s>测试环境</s><?php } else {?><i class="fa fa-check-circle"></i> 测试环境<?php } ?></button>
                                        <?php } ?>
                                        <?php if ($value['rank'] == 2) {?>
                                        <button class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i> 生产环境</button>
                                        <?php } ?>
                                      </td>
                                      <td>
                                        <?php if ($value['state'] == 0) {?>
                                        <button class="btn btn-default btn-xs"><i class="fa fa-coffee"></i> 待测</button>
                                        <?php } ?>
                                        <?php if ($value['state'] == 1) {?>
                                        <button class="btn btn-primary btn-xs"><i class="fa fa-clock-o"></i> 测试中……</button>
                                        <?php } ?>
                                        <?php if ($value['state'] == -3) {?>
                                        <button class="btn btn-danger btn-xs"><i class="fa fa-exclamation-circle"></i> 不通过</button>
                                        <?php } ?>
                                        <?php if ($value['state'] == 3) {?>
                                        <button class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i> 通过</button>
                                        <?php } ?>
                                        <?php if ($value['state'] == 5) {?>
                                        <button class="btn btn-success btn-xs"><i class="fa fa-exclamation-circle"></i> 已被后续版本覆盖</button>
                                        <?php } ?>
                                      </td>
                                      <td><?php echo $value['add_user'] ? '<a href="/conf/profile/'.$value['add_user'].'">'.$users[$value['add_user']]['realname'].'</a>' : '-';?></td>
                                      <td><?php echo $value['last_user'] ? '<a href="/conf/profile/'.$value['last_user'].'">'.$users[$value['last_user']]['realname'].'</a>' : '-';?></td>
                                      <td class="table-action">
                                        <?php if ($value['tice'] == 0 && $row['status'] == 1) {?><button class="btn btn-success btn-xs tice"  id="tice-<?php echo $value['id'];?>" testid="<?php echo $value['id'];?>"><i class="fa fa-send"></i> 提测</button><?php }?>
                                        <?php if ($value['tice'] == -1 ) {?><button class="btn btn-warning btn-xs tice" id="tice-<?php echo $value['id'];?>" testid="<?php echo $value['id'];?>"><i class="fa fa-exclamation-circle"></i> 提测失败,请再提测</button><?php }?>
                                        <?php if ($value['tice'] == 3 ) {?><button class="btn btn-white btn-xs" testid="<?php echo $value['id'];?>" disabled><img src="/static/images/loaders/loader3.gif" alt="" /> 提测中……</button><?php }?>
                                        <?php if ($value['state'] == 3 && $value['rank'] == 1 && $value['tice'] < 5 && $users[$value['accept_user']]['role'] == 1) {?><button class="btn btn-success btn-xs cap_production"  id="cap_production-<?php echo $value['id'];?>" testid="<?php echo $value['id'];?>"><i class="fa fa-send"></i> 发布到生产环境</button><?php }?>
                                        <?php if ($value['tice'] == 5 ) {?><button class="btn btn-white btn-xs" disabled><img src="/static/images/loaders/loader3.gif" alt="" /> 发布中……</button><?php }?>
                                        <?php if ($row['status'] == 1) {?>
                                        <?php if ($value['tice'] < 1) {?>
                                        <a class="btn btn-white btn-xs" href="/test/edit/<?php echo $row['id'];?>/<?php echo $value['id'];?>"><i class="fa fa-pencil"></i> 编辑</a>
                                        <a class="btn btn-white btn-xs delete-row" href="javascript:;" issueid="<?php echo $row['id'];?>" testid="<?php echo $value['id'];?>"><i class="fa fa-trash-o"></i> 删除</a>
                                        <?php }?>
                                        <?php }?>
                                        <?php if ($value['tice'] == 1 && $value['state'] == 1 && $value['rank'] == 1) {?>
                                        <div class="btn-group">
                                          <button type="button" class="btn btn-xs btn-primary">更改测试状态</button>
                                          <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                          </button>
                                          <ul class="dropdown-menu" role="menu">
                                            <li><a href="javascript:;" class="success" testid="<?php echo $value['id'];?>">通过</a></li>
                                            <li><a href="javascript:;" class="fail" testid="<?php echo $value['id'];?>">不通过</a></li>
                                          </ul>
                                        </div><!-- btn-group -->
                                        <?php }?>
                                      </td>
                                    </tr>
                                    <?php
                                        }
                                      } else {
                                    ?>
                                    <tr><td colspan="10" align="center">无提测信息</td></tr>
                                    <?php } ?>
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
<script src="/static/js/jquery.gritter.min.js"></script>

<script src="/static/js/custom.js"></script>
<script>
  function changeIssueStatus(obj1,obj2,obj3) {
    $(obj1).click(function(){
      var c = confirm(obj3);
      if(c) {
        id = $(this).attr("reposid");
        $.ajax({
          type: "GET",
          url: "/issue/"+obj2+"/"+id,
          dataType: "JSON",
          success: function(data){
            if (data.status) {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-success',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
              setTimeout(function(){
                location.href = data.url;
              }, 2000);
            } else {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-danger',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
            };
          }
        });
      }
    });
  }

  function changeTestStatus(obj1,obj2,obj3) {
    $(obj1).click(function(){
      var c = confirm(obj3);
      if(c) {
        id = $(this).attr("testid");
        $.ajax({
          type: "GET",
          url: "/test/"+obj2+"/"+id,
          dataType: "JSON",
          success: function(data){
            if (data.status) {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-success',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
              setTimeout(function(){
                location.href = data.url;
              }, 2000);
            } else {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-danger',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
            };
          }
        });
      }
    });
  }

  $(document).ready(function(){
    $("#del").click(
      changeIssueStatus('#del','del','确认要删除吗？')
    );
    $("#close").click(
      changeIssueStatus('#close','close','确认要关闭吗？')
    );
    $("#resolve").click(
      changeIssueStatus('#resolve','resolve','确认要解决吗？')
    );
    $(".success").click(function(){
      var c = confirm('确认要通过吗？');
      if(c) {
        id = $(this).attr("testid");
        $.ajax({
          type: "GET",
          url: "/test/success/"+id,
          dataType: "JSON",
          success: function(data){
            if (data.status) {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-success',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
              setTimeout(function(){
                location.href = data.url;
              }, 2000);
            } else {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-danger',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
            };
          }
        });
      }
    });
    $(".fail").click(function(){
      var c = confirm('确认要不通过，驳回吗？');
      if(c) {
        id = $(this).attr("testid");
        $.ajax({
          type: "GET",
          url: "/test/fail/"+id,
          dataType: "JSON",
          success: function(data){
            if (data.status) {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-success',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
              setTimeout(function(){
                location.href = data.url;
              }, 2000);
            } else {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-danger',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
            };
          }
        });
      }
    });

    $(".tice").click(function(){
      $(this).attr("disabled", true);
      id = $(this).attr("testid");
      $.ajax({
        type: "GET",
        url: "/test/tice/"+id,
        dataType: "JSON",
        success: function(data){
          if (data.status) {
            jQuery.gritter.add({
              title: '提醒',
              text: data.message,
                class_name: 'growl-success',
                image: '/static/images/screen.png',
              sticky: false,
              time: ''
            });
            setTimeout(function(){
              location.href = data.url;
            }, 2000);
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
    });

    //发布到生产环境
    $(".cap_production").click(function(){
      $(this).attr("disabled", true);
      id = $(this).attr("testid");
      $.ajax({
        type: "GET",
        url: "/test/cap_production/"+id,
        dataType: "JSON",
        success: function(data){
          if (data.status) {
            jQuery.gritter.add({
              title: '提醒',
              text: data.message,
                class_name: 'growl-success',
                image: '/static/images/screen.png',
              sticky: false,
              time: ''
            });
            setTimeout(function(){
              location.href = data.url;
            }, 2000);
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
    });

    $(".delete-row").click(function(){
      var c = confirm("确认要删除吗？");
      if(c) {
        testid = $(this).attr("testid");
        issueid = $(this).attr("issueid");
        $.ajax({
          type: "GET",
          url: "/test/del/"+testid+"/"+issueid,
          dataType: "JSON",
          success: function(data){
            if (data.status) {
              $("#tr-"+testid).fadeOut(function(){
                $("#tr-"+testid).remove();
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
            };
          }
        });
      }
    });

  });
</script>

</body>
</html>
