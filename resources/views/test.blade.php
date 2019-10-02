
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet'
        type='text/css' />
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap-theme.css" rel='stylesheet' type='text/css' />-->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" rel='stylesheet'
        type='text/css' />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.2/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.16.2/axios.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/components/cust_list.js"></script>

    <title>iHala客戶列表</title>
    <!--<meta name="csrf-token" content="{{ csrf_token() }}">-->
</head>
@include('components.header')
<style>
ol,
ul {
    list-style: none;
    padding-inline-start: 0.5em;
}
</style>

<body>
    <div id="app">
        <cust_list></cust_list>
    </div>

    <div class="flex-center position-ref full-height">

        <script type="text/x-template" id="cust_list" style="display:none">
            <div id="list" class="list">
            <div class="container">
                
				<div class="modal fade" id="custModal" tabindex="-1" role="dialog" aria-labelledby="custModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="custModalLabel">修改資料</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    
                                    <!--<input ref="csrf" type="hidden" name="ci_csrf_token" value="">-->
                                    <input ref="csrf" type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input  type="hidden" class="form-control" id="attending_date" :value="modal.attending_date" v-model="modal.attending_date">
									<div class="form-group col-md-12">
                                        <label for="user_name">姓名:</label>
                                        <input type="text" class="form-control" id="user_name" :value="modal.name" v-model="modal.name">
                                    </div>
									<div class="form-group col-md-6">
                                        <label for="super8_id">Super8 ID:</label>
                                        <input type="text" class="form-control" id="s_id"  :value="modal.s_id" v-model="modal.s_id">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="obj_id">Object ID:</label>
                                        <input type="text" class="form-control" id="obj_id"  :value="modal.obj_id" v-model="modal.obj_id">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="obj_id">CRM ID:</label>
                                        <input type="text" class="form-control" id="c_id"  :value="modal.c_id" v-model="modal.c_id">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="comm_id">Comm ID:</label>
                                        <input type="text" class="form-control" id="comm_id"  :value="modal.comm_id" v-model="modal.comm_id">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="phone">電話:</label>
                                        <input type="number" class="form-control" id="phone" :value="modal.phone" v-model="modal.phone">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" :value="modal.email" v-model="modal.email">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="origin_name">顯示名稱:</label>
                                        <input type="text" class="form-control" id="origin_name" :value="modal.origin_name" v-model="modal.origin_name">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="sex">姓別:</label>
                                        <select class="form-control" id="sex" :value="modal.sex" v-model="modal.sex">
                                            <option value="男">男</option>
                                            <option value="女">女</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="birth">生日:</label>
                                        <input type="date" class="form-control" id="birth" :value="modal.birth" v-model="modal.birth">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="cNumber">病歷號:</label>
                                        <input type="text" class="form-control" id="cNumber" :value="modal.cNumber" v-model="modal.cNumber">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="treatment">療程:</label>
                                        <select class="form-control" id="treatment" :value="modal.treatment" v-model="modal.treatment">
                                            <option value="OD">OD</option>
                                            <option value="SD">SD</option>
                                            <option value="OR">OR</option>
                                            <option value="SR">SR</option>
                                            <option value="IVF">IVF</option>
                                            <option value="IC">IC</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-md-8">
                                        <label for="about">關於:</label>
                                        <input type="textarea" class="form-control" id="about" :value="modal.about" v-model="modal.about">
                                    </div>                                    
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                                <button v-if="modal_status === 0" type="button" class="btn btn-primary" data-dismiss="modal" @click="updateUserData(modal)">儲存</button>
                                <button v-if="modal_status === 1" type="button" class="btn btn-primary" data-dismiss="modal" @click="insertUserData(modal)">新增</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                    <button type="button" class="btn btn-sm btn-primary btn-create" @click="downloadUsersList()" >輸出客戶名單</button>
                                    <button type="button" class="btn btn-sm btn-primary btn-create" data-toggle="modal" data-target="#custModal"  @click="createUserModal()">新增</button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-list table-responsive">
                                <thead>
                                    <tr>
                                        <th class="col-md-1"><em class="fa fa-cog"></em></th>
                                        <th class="col-md-1">姓名</th>
                                        <th class="col-md-2">id</th>
                                        <th class="col-md-2">個人資料</th>
                                        <th class="col-md-2">醫療資料</th>
                                        <th class="col-md-2">關於</th>
                                        <th class="col-md-2">資料時間</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <tr v-if="user.user_id !== '0'" v-for="(user, index) in users"  @click="">
                                        <td align="center">
                                            <a class="btn btn-default" data-toggle="modal" data-target="#custModal" @click="updateUserModal(user)">
                                                <em class="fa fa-pencil"></em>
                                            </a>
                                            <a class="btn btn-danger" @click="">
                                                <i class="fa fa-sign-in" aria-hidden="true"></i>
                                            </a>
                                            <!--
                                            <a class="btn btn-danger" @click="deleteUserData(user)">
                                                <em class="fa fa-trash"></em>
                                            </a>
                                            -->
                                        </td>
                                        <td>@{{user.name}}</td>
                                        <td>
                                            <ol>
                                                <li>Super8 id： @{{user.s_id}}</li>
                                                <li>CRM id：@{{user.c_id}}</li>
                                                <li>通訊軟體id：@{{user.comm_id}}</li>
                                            </ol>                                            
                                        </td>
                                              
                                        <td>
                                            <ol>
                                                <li><em class="fa fa-phone">: @{{user.phone}}</li>
                                                <li><em class="fa fa-envelope">：@{{user.email}}</li>
                                                <li>顯示名稱: @{{user.origin_name}}</li>
                                                <li>姓別：@{{user.sex}}</li>
                                                <li>生日：@{{user.birth}}</li>
                                            </ol>
                                        </td>
                                        <td>
                                            <ol>
                                                <li>病歷號：@{{user.cNumber}}</li>
                                                <li>療程：@{{user.treatment}}</li>
                                            </ol>
                                        </td>
                                        <td>@{{user.about}}</td>
										<td>
                                            <ol>
                                                <li>建立時間：@{{user.create_time}}</li>
                                                <li>更新時間：@{{user.update_time}}</li>
                                            </ol>
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
                                            <a v-if="page_start + page <= pages" class="btn" @click="getUsersByPage(page_start + page)">@{{ page_start + page }}</a>
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
        </div>               
    </script>
    </div>
</body>

</html>