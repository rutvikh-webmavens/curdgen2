@can('table_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.tables.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.table.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.table.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-menuTables">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.table.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.table.fields.menu') }}
                        </th>
                        <th>
                            {{ trans('cruds.table.fields.field_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.table.fields.field_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.table.fields.field_title') }}
                        </th>
                        <th>
                            {{ trans('cruds.table.fields.in_list') }}
                        </th>
                        <th>
                            {{ trans('cruds.table.fields.in_create') }}
                        </th>
                        <th>
                            {{ trans('cruds.table.fields.in_edit') }}
                        </th>
                        <th>
                            {{ trans('cruds.table.fields.is_required') }}
                        </th>
                        <th>
                            {{ trans('cruds.table.fields.sort_order') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tables as $key => $table)
                        <tr data-entry-id="{{ $table->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $table->id ?? '' }}
                            </td>
                            <td>
                                {{ $table->menu->model_name ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Table::FIELD_TYPE_SELECT[$table->field_type] ?? '' }}
                            </td>
                            <td>
                                {{ $table->field_name ?? '' }}
                            </td>
                            <td>
                                {{ $table->field_title ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $table->in_list ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $table->in_list ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $table->in_create ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $table->in_create ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $table->in_edit ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $table->in_edit ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $table->is_required ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $table->is_required ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $table->sort_order ?? '' }}
                            </td>
                            <td>
                                @can('table_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.tables.show', $table->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('table_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.tables.edit', $table->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('table_delete')
                                    <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('table_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tables.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-menuTables:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection