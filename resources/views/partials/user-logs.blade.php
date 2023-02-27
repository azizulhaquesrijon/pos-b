
<span class="btn btn-info btn-{{ isset($icon_size) ? $icon_size : 'xs' }} popover-success"
      data-rel="popover"
      data-placement="top"
      data-trigger="hover"
      data-original-title="<i class='ace-icon fa fa-info-circle green'></i> Log Information"
      data-content="<p>Created By: {{ optional($data->created_user)->name }}.</p> <p> Created At : {{ $data->created_at }} </p>

       <hr/>
       <p>Updated By: {{ optional($data->updated_user)->name }}.</p> <p> Updated At : {{ $data->updated_at }} </p>">
    <i class="fa fa-info-circle"></i>
</span>
