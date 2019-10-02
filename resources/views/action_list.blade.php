
<!doctype html>
<html lang="@{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    
    @include('components.head')
    <script type="text/javascript" src="/js/components/action_list.js"></script>
    <title>iHala資料傳輸列表</title>
    <!--<meta name="csrf-token" content="@{{ csrf_token() }}">-->
</head>
@include('components.menu')
<style>
ol,
ul {
    list-style: none;
    padding-inline-start: 0.5em;
}
.container{
    width:95%;
}

.modal-dialog {
    width: 80%;
}

</style>

<body>
    <div id="app">
        <action_list></action_list>
    </div>

    <div class="flex-center position-ref full-height">

        <script type="text/x-template" id="action_list" style="display:none">
            <div id="list" class="list">
            <div class="container">
                <div class="row">                    
                    <div class="col-md-12">
                        <div class="panel panel-default panel-table">
                        <div class="panel-heading">
                            <div class="row">
                                
								<div class="col col-xs-6">
									<input class="search-input" v-model="filter" type="text" placeholder="搜尋客戶名或諮詢師名或電話"/>
                                    <button type="button" class="btn btn-primary" @click="searchUsers()">搜尋</button><br>
									<h3 class="panel-title">iHala後台名單</h3>
                                </div>
								
                                <div class="col col-xs-6 text-right">
                                    <!--
                                    <button type="button" class="btn btn-sm btn-primary btn-create" @click="downloadUsersList()" >輸出客戶名單</button>
                                    <button type="button" class="btn btn-sm btn-primary btn-create" data-toggle="modal" data-target="#actionModal"  @click="createUserModal()">新增</button>
                                    -->
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-list table-responsive">
                                <thead>
                                    <tr>
                                        <th class="col-md-1"><em class="fa fa-cog"></em></th>
                                        <th class="col-md-2">id</th>
                                        <th class="col-md-1">data</th>
                                        <th class="col-md-1">result</th>
                                        <th class="col-md-1">msg</th>
                                        <th class="col-md-1">response_obj</th>
                                        <th class="col-md-1">是否匯入CRM</th>
                                        <th class="col-md-1">資料時間</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <tr v-for="(action, index) in actions">
                                        <td align="center">
                                            <a v-if="action.result =='fail'" class="btn btn-danger" data-toggle="modal" data-target="#actionModal" @click="updateModal(action)">
                                                <i class="fa fa-sign-in" aria-hidden="true"></i>
                                            </a>
                                            <!--
                                            <a class="btn btn-danger" @click="deleteUserData(user)">
                                                <em class="fa fa-trash"></em>
                                            </a>
                                            -->
                                        </td>
                                        <td>
                                            <ol>
                                                <li>ID： @{{action.id}}</li>
                                                <li>CRM ID：@{{action.c_id}}</li>
                                                <li>iHala ID：@{{action.ihala_id}}</li>
                                            </ol> 
                                        </td>     
                                        
                                        <td>
                                            @{{action.data}}                                        
                                        </td>
                                        
                                              
                                        <td>
                                            @{{action.result}}
                                        </td>
                                        <td>
                                            @{{action.msg}}
                                        </td>
                                        <td>
                                            @{{action.response_obj}}
                                        </td>
                                        <td>
                                            @{{action.importy_flag}}
                                        </td>
										<td>     
                                            @{{action.update_time}}  
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col col-xs-4">
                                    <p>Page @{{current_page}} of @{{pages}}</p>
                                </div>
                        
                                <div class="col col-xs-8">
                                    <ul class="pagination hidden-xs pull-right">
                                        <li v-if="current_page != 1" class="page-item">
                                            <a class="page-link" aria-label="Previous" @click="current_page = current_page - 1">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>
                                        <li v-if="pages !== 0" v-for="page in 10" :class="{active: page_start + page === current_page ? true: false}">
                                            <a v-if="page_start + page <= pages" class="btn" @click="getActionsByPage(page_start + page)">@{{ page_start + page }}</a>
                                        </li>        
                                        <li class="page-item">
                                            <a v-if="current_page < pages" class="page-link" aria-label="Next" @click="current_page = current_page + 1">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>                                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
				<div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="actionModalLabel">修改資料</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <input ref="csrf" type="hidden" name="_token" value="@{{ csrf_token() }}">
									<div class="form-group col-md-12">
                                        <label for="crm_id">欲更新的CRM ID</label>
                                        <select class="form-control" v-model="crmid_selected">
                                            <option v-for="(cust, index) in crm_data" v-bind:value="cust['id']">@{{cust['id']}}</option>
                                        </select>
                                        <!--<span>Selected: @{{ crmid_selected }}</span>-->
                                    </div>
                                    
                                    <div >
                                    
                                        <ul class="form-group col-md-6">
                                            <li>客戶填的資料</li><br>
                                            <li  v-for="(item, index) in action_data">   
                                                <label>@{{index}}:</label>
                                                <input type="text" class="form-control"  v-model="action_data[index]" >
                                                <!--<label style="color:blue;">@{{item}}</label>-->   
                                            </li>
                                        </ul>
                                    </div>
                                    <div v-for="(cust, index) in crm_data" >
                                        <ul class="form-group col-md-6">
                                            <li>CRM內的資料</li><br>
                                            <li>
                                                <div>
                                                    <label>ID:</label>
                                                    <label style="color:blue;">@{{cust.id}}</label>
                                                </div>
                                            </li>
                                            <li v-for="(item,i) in cust">
                                                <div v-if="i=='id'">

                                                </div>
                                                <div v-else>
                                                    <label>@{{i}}:</label>
                                                    <!--<input type="text" v-bind:value="cust[i]">-->
                                                    <label style="color:blue;">@{{cust[i]}}</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
									                                  
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal" @click="updateDataToCrm(action_data)">傳送至CRM</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>               
    </script>
    </div>
</body>

</html>