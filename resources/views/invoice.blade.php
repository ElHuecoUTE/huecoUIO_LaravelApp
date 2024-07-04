<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    html {
      color: #333;
    }

    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
    }

    .title {
      font-size: 2rem;
      font-weight: 700;
      color: #333;
    }

    .sub_header {
      font-size: 1.1rem;
      font-weight: 400;
      color: #666;
    }

    .sub_title {
      margin-top: 2rem;
      font-size: 1.4rem;
      color: #333;
    }

    table {
      font-family: 'Arial', sans-serif;
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
      background-color: #efefef;
      border-radius: 6px;
    }

    th {
      font-family: 'Arial', sans-serif;
      color: #333;
      font-weight: 600;
      padding: 0.5rem;
    }

    td {
      font-family: 'Arial', sans-serif;
      padding: 0.5rem;
      border-bottom: 1px solid #f2f2f2;
      text-align: center;
      text-transform: capitalize;
    }
  </style>
</head>
<body>
  <div>
    <h1 class="title">HuecoUTE - Factura de {{ $client->cli_nom }} {{ $client->cli_ape }}</h1>
    <p class="sub_header">
      {{ $client->cli_ema }}
      -
      {{ $client->cli_tel }}
      -
      {{ $client->cli_dir }}
    </p>

    <p class="sub_title">Pedidos</p>

    @if (count($orders) == 0)
      <p>No hay pedidos</p>
    @else
      <table>
        <tr>
          <th>ID</th>
          <th>Total</th>
          <th>Estado</th>
          <th>Fecha</th>
        </tr>
        @foreach ($orders as $order)
          <tr>
            <td>{{ $order->ped_id }}</td>
            <td>${{ $order->ped_tot }}</td>
            <td>{{ $order->EstadoPedido->est_ped_nom }}</td>
            <td>{{ $order->created_at }}</td>
          </tr>
        @endforeach
      </table>
    @endif
  </div>
</body>
</html>
