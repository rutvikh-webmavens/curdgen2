@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('table_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.tables.create') }}">
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
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Table">
                            <thead>
                                <tr>
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
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($menus as $key => $item)
                                                <option value="{{ $item->model_name }}">{{ $item->model_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\Models\Table::FIELD_TYPE_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tables as $key => $table)
                                    <tr data-entry-id="{{ $table->id }}">
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
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.tables.show', $table->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('table_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.tables.edit', $table->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('table_delete')
                                                <form action="{{ route('frontend.tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('table_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.tables.massDestroy') }}",
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
  let table = $('.datatable-Table:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})

</script>
@endsection