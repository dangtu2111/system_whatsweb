@php 
$statistic = new App\Facades\Statistic;
@endphp
<section class="section">
  {!! isset($header) && $header == true ? '' : '<div class="container">' !!}
    @if(isset($header) && $header == true)
    <div class="section-header">
      <h1>Dashboard</h1>
    </div>
    @endif
    <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-link"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Onsite</h4>
            </div>
            <div class="card-body">
              <div id="onsite"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-link"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Link</h4>
            </div>
            <div class="card-body">
              <div id="total-link"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-danger">
            <i class="fas fa-chart-line"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Today Visit</h4>
            </div>
            <div class="card-body">
              <div id="today-visit"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-warning">
            <i class="fas fa-chart-line"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Yesterday Visit</h4>
            </div>
            <div class="card-body">
              <div id="yesterday-visit"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-success">
            <i class="fas fa-chart-line"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>7 Days Visit</h4>
            </div>
            <div class="card-body">
              <div id="seven-days-visit"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header">
            <h4>Last 7 Days</h4>
          </div>
          <div class="card-body">
            <canvas id="myChart" height="158"></canvas>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card gradient-bottom">
          <div class="card-header">
            <h4>Top 5 Links</h4>
          </div>
          <div class="card-body" id="top-5-scroll">
            @php 
              $no = 1; 
              $stat = $statistic->top(5); 
            @endphp
            @if(count($stat) > 0)
            <ul class="list-unstyled list-unstyled-border">
              @foreach($stat as $link)
              <li class="media">
                <div class="d-inline-block text-65 bg-primary text-white rounded text-center mr-3" data-width="65" data-height="65">
                  {{$no++}}
                </div>
                <div class="media-body">
                  <div class="float-right"><div class="font-weight-600 text-muted text-small">{{$link->hit}} Hits</div></div>
                  <div class="media-title"><a href="{{ route('slug', $link->slug) }}" target="_blank">{{ $link->slug }}</a></div>
                  <div class="mt-1">
                    <div class="text-job text-muted">
                      {{$link->created_at->diffForHumans()}}
                    </div>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
            @else
            <p class="lead text-center">No Data</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  {!! isset($header) && $header == true ? '' : '</div>' !!}
</section>

@section('plugins_js')
  <script src="{{asset('dist/modules/chart.min.js')}}"></script>
  <script src="{{asset('dist/modules/sweetalert/sweetalert.min.js')}}"></script>
@stop
@section('scripts')
<script>
  @if(is_demo())
  swal('Attention', 'You are in demo mode, some features are not activated and will display 403 page.', 'warning');
  @endif

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")
  },
})
$.ajax({
  url: '{{ route('stats.chart7days', [user_prefix()]) }}',
  type: 'POST',
  beforeSend: function() {
    $.cardProgress($("#myChart").closest('.card'));
  },
  complete: function() {
    $.cardProgressDismiss($("#myChart").closest('.card'));
  },
  success: function(data) {
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: JSON.parse(data.data.labels),
        datasets: [{
          label: 'Visit',
          data: JSON.parse(data.data.values),
          borderWidth: 2,
          backgroundColor: '#005e54',
          borderWidth: 0,
          borderColor: 'transparent',
          pointBorderWidth: 0,
          pointRadius: 3.5,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: '#005e54',
        }]
      },
      options: {
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              // display: false,
              drawBorder: false,
              color: '#f2f2f2',
            },
            ticks: {
              beginAtZero: true,
              min: 0,
              callback: function(value, index, values) {
                if (Math.floor(value) === value) {
                  return value;
                }
              }
            }
          }],
          xAxes: [{
            gridLines: {
              display: false,
              tickMarkLength: 15,
            }
          }]
        },
      }
    });
  }
})

function getStatistic(url, el) {
  $.ajax({
    url: url,
    type: 'POST',
    beforeSend: function() {
      el.html('Counting ...');
    },
    success: function(data) {
      el.html(data);
    }
  })
}

getStatistic('{{route('stats.totalLink', [user_prefix()])}}', $("#total-link"));
getStatistic('{{route('stats.todayVisit', [user_prefix()])}}', $("#today-visit"));
getStatistic('{{route('stats.yesterdayVisit', [user_prefix()])}}', $("#yesterday-visit"));
getStatistic('{{route('stats.sevenDaysVisit', [user_prefix()])}}', $("#seven-days-visit"));
getStatistic('{{route('stats.activeVisitors', [user_prefix()])}}', $("#onsite"));

</script>
@stop