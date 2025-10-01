<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pengunjung {{ $period }}</title>
  <style>
    @page html {
      size: A4;
    }
    .container {
      margin: 0 auto;
      max-width: 1024px;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th {
      font-size: 1.15rem;
      font-weight: 700;
    }
    th, td {
      border: 1px solid black;
      padding: 2.5px;
    }
    tfoot {
      font-weight: 700;
    }
    .header {
      border-bottom: 1px solid black;
      padding: 5px 0;
      text-align: center;
      font-weight: 700;
    }
    .h1 {
      font-size: 1.5rem;
    }
    .h2 {
      font-size: 1.4rem;
    }
    .h3 {
      font-size: 1.2rem;
    }
    .mt-5 {
      margin-top: 1.25rem;
    }
    .period {
      font-weight: 700;
      font-size: 1.2rem;
    }
    .text-center {
      text-align: center;
    }
    td.weekend {
      background-color: red;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <div class="h2">
        DATA PENGUNJUNG
      </div>
      <div class="h1">
        UPT {{ config('app.upt') }}
      </div>
      <div class="h3">
        DINAS PARIWISATA DAN KEBUDAYAAN KABUPATEN GARUT
      </div>
    </div>
    <div class="mt-5 period">
      {{ $period }}
    </div>
    <table class="table mt-5">
      <thead>
        <tr>
          <th>Tanggal</th>
          @foreach($types as $type)
          <th>{{ $type->name }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach($transactions as $tr)
        <tr>
          <td class="text-center">{{ date('j', strtotime($tr['date'])) }}</td>
          @foreach($types as $type)
          <td class="text-center">{{ !empty($tr[$type->id]) ? $tr[$type->id] : '-' }}</td>
          @endforeach
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td class="text-center">Total</td>
          @foreach($types as $type)
          <td class="text-center">{{ $total[$type->id] }}</td>
          @endforeach
        </tr>
      </tfoot>
    </table>
  </div>
</body>

</html>