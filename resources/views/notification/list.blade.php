@extends('layouts.master')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('assets/examples/css/apps/mailbox.css')}}">
@endsection

@section('content')
  <div class="page bg-white">
    <!-- Mailbox Sidebar -->
    <div class="page-aside">
      <div class="page-aside-switch">
        <i class="icon md-chevron-left" aria-hidden="true"></i>
        <i class="icon md-chevron-right" aria-hidden="true"></i>
      </div>
      <div class="page-aside-inner page-aside-scroll">
        <div data-role="container">
          <div data-role="content">
            <div class="page-aside-section">
              <div class="list-group">
                <a class="list-group-item active" href="javascript:void(0)">
                  <span class="tag tag-pill tag-danger">6</span><i class="icon md-calendar" aria-hidden="true"></i>Leave Approval</a>
                <a class="list-group-item" href="javascript:void(0)"><i class="icon md-calendar" aria-hidden="true"></i>Leave Request</a>
                <a class="list-group-item" href="javascript:void(0)">
                  <span class="tag tag-pill tag-info">2</span><i class="icon md-money" aria-hidden="true"></i>Loan Request</a>
                <a class="list-group-item" href="javascript:void(0)"><i class="icon md-money" aria-hidden="true"></i>Loan Approval</a>
                <a class="list-group-item" href="javascript:void(0)"><i class="icon md-money-box" aria-hidden="true"></i>Payroll</a>
                <a class="list-group-item" href="javascript:void(0)"><i class="icon md-chart" aria-hidden="true"></i>Performance</a>
              </div>
            </div>
           
          </div>
        </div>
      </div>
    </div>
    <!-- Mailbox Content -->
    <div class="page-main">
      <!-- Mailbox Header -->
      <div class="page-header">
        <h1 class="page-title">Notification Center</h1>
        <div class="page-header-actions">
          <form>
            <div class="input-search input-search-dark">
              <i class="input-search-icon md-search" aria-hidden="true"></i>
              <input type="text" class="form-control" name="" placeholder="Search...">
            </div>
          </form>
        </div>
      </div>
      <!-- Mailbox Content -->
      <div id="mailContent" class="page-content page-content-table" data-plugin="asSelectable">
        <!-- Actions -->
        <div class="page-content-actions">
          
          <div class="actions-main">
            <span class="checkbox-custom checkbox-primary checkbox-lg inline-block vertical-align-bottom">
              <input type="checkbox" class="mailbox-checkbox selectable-all" id="select_all"
              />
              <label for="select_all"></label>
            </span>
            
          </div>
        </div>
        <!-- Mailbox -->
        <table id="mailboxTable" class="table" data-plugin="animateList" data-animate="fade"
        data-child="tr">
          <tbody>
          	@foreach(Auth::user()->unreadNotifications as $notification)
            <tr id="mid_1" data-url="{{url('userprofile/notification').'?notification_id='.$notification->id}}" data-toggle="slidePanel">
              <td class="cell-60">
                <span class="checkbox-custom checkbox-primary checkbox-lg">
                  <input type="checkbox" class="mailbox-checkbox selectable-item" id="mail_mid_1"
                  />
                  <label for="mail_mid_1"></label>
                </span>
              </td>
              <td class="cell-30 responsive-hide">
                <span class="checkbox-important checkbox-default">
                  <input type="checkbox" class="mailbox-checkbox mailbox-important" id="mail_mid_1_important"
                  />
                  <label for="mail_mid_1_important"></label>
                </span>
              </td>
              <td class="cell-60 responsive-hide ">
                <a class="avatar" href="javascript:void(0)">
                  <i class="icon {{isset($notification->data['icon'])?$notification->data['icon']:'md-notifications'}}  " ></i>
                </a>
              </td>
              <td>
                <div class="content">
                  <div class="title">{{$notification->data['type']}}</div>
                  <div class="abstract">{{$notification->data['subject']}}</div>
                </div>
              </td>
              <td class="cell-30 responsive-hide">
              </td>
              <td class="cell-130">
                <div class="time">{{$notification->created_at->diffForHumans()}}</div>
               {{--  <div class="identity"><i class="md-circle red-600" aria-hidden="true"></i>Work</div> --}}
              </td>
            </tr>
           @endforeach
          </tbody>
        </table>
        <!-- pagination -->
        <ul data-plugin="paginator" data-total="50" data-skin="pagination-gap"></ul>
      </div>
    </div>
  </div>
  <div class="site-action" data-plugin="actionBtn">
    <button type="button" data-action="add" class="site-action-toggle btn-raised btn btn-success btn-floating">
     
      <i class="back-icon md-close animation-scale-up" aria-hidden="true"></i>
    </button>
    <div class="site-action-buttons">
      <button type="button" data-action="trash" class="btn-raised btn btn-success btn-floating animation-slide-bottom">
        <i class="icon md-delete" aria-hidden="true"></i>
      </button>
      <button type="button" data-action="inbox" class="btn-raised btn btn-success btn-floating animation-slide-bottom">
        <i class="icon md-inbox" aria-hidden="true"></i>
      </button>
    </div>
  </div>
  <!-- Create New Messages Modal -->
  <div class="modal fade" id="addMailForm" aria-hidden="true" aria-labelledby="addMailForm"
  role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
          <h4 class="modal-title">Create New Messages</h4>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <select id="topicTo" class="form-control" data-plugin="select2" multiple="multiple"
              data-placeholder="To:">
                <optgroup label="">
                  <option value="AK">Alaska</option>
                  <option value="HI">Hawaii</option>
                </optgroup>
              </select>
            </div>
            <div class="form-group">
              <select id="topicSubject" class="form-control" data-plugin="select2" multiple="multiple"
              data-placeholder="Subject:">
                <optgroup label="">
                  <option value="AK">Alaska</option>
                  <option value="HI">Hawaii</option>
                </optgroup>
              </select>
            </div>
            <div class="form-group">
              <textarea name="content" data-provide="markdown" data-iconlibrary="fa" rows="10"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer text-xs-left">
          <button class="btn btn-primary" data-dismiss="modal" type="submit">Send</button>
          <a class="btn btn-sm btn-white btn-pure" data-dismiss="modal" href="javascript:void(0)">Cancel</a>
        </div>
      </div>
    </div>
  </div>
  <!-- End Create New Messages Modal -->
  <!-- Add Label Form -->
  <div class="modal fade" id="addLabelForm" aria-hidden="true" aria-labelledby="addLabelForm"
  role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
          <h4 class="modal-title">Add New Label</h4>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <input type="text" class="form-control" name="lablename" placeholder="Label Name"
              />
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" data-dismiss="modal" type="submit">Save</button>
          <a class="btn btn-sm btn-white btn-pure" data-dismiss="modal" href="javascript:void(0)">Cancel</a>
        </div>
      </div>
    </div>
  </div>
  <!-- End Add Label Form -->
@endsection
@section('scripts')
  <script src="{{ asset('assets/js/App/Mailbox.js')}}"></script>
  <script src="{{ asset('assets/examples/js/apps/mailbox.js')}}"></script>
@endsection