<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{$quincena}}</title>
        <style>
            .clearfix:after {
                content: "";
                display: table;
                clear: both;
            }

            a {
                color: #5D6975;
                text-decoration: underline;
            }

            body {
                position: relative;
                width: 27.5cm;
                height: 29.7cm;
                margin: 0 0;
                color: #001028;
                background: #FFFFFF;
                font-family: Arial, sans-serif;
                font-size: 12px;
                font-family: Arial;
            }

            header {
                padding: 10px 0;
                margin-bottom: 30px;
            }

            #logo {
                text-align: center;
                margin-bottom: 10px;
            }

            #logo img {
                width: 90px;
            }

            h1 {
                border-top: 1px solid #5D6975;
                border-bottom: 1px solid #5D6975;
                color: #5D6975;
                font-size: 2.4em;
                line-height: 1.4em;
                font-weight: normal;
                text-align: center;
                margin: 0 0 2px 0;
            }
            
            #project span {
                color: #5D6975;
                text-align: right;
                width: 52px;
                margin-right: 10px;
                display: inline-block;
                font-size: 0.8em;
            }

            #company {
                float: right;
                text-align: right;
            }

            #project div,
            #company div {
                white-space: nowrap;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 30px;
            }

            table tr:nth-child(2n-1) td {
                background: #F5F5F5;
            }

            table th,
            table td {
                text-align: center;
            }

            table th {
                padding: 5px 5px;
                color: #5D6975;
                border-bottom: 1px solid #C1CED9;
                white-space: nowrap;
                font-weight: normal;
            }

            table .service,
            table .desc {
                text-align: left;
            }

            table td {
                /* padding: 20px; */
                text-align: right;
            }

            table td.service,
            table td.desc {
                vertical-align: top;
            }

            table td.unit,
            table td.qty,
            table td.total {
                font-size: 1.2em;
            }

            table td.grand {
                border-top: 1px solid #5D6975;
                ;
            }

            #notices .notice {
                color: #5D6975;
                font-size: 1.2em;
            }

            footer {
                color: #5D6975;
                width: 100%;
                height: 30px;
                position: absolute;
                bottom: 0;
                border-top: 1px solid #C1CED9;
                padding: 8px 0;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <header class="clearfix">
        <h1>Resumen Quincena <?php echo date("d M/y", strtotime($resumen['fecha_max']));?></h1>
        </header>
        <main>
            @foreach ($resumen['resumen_gd_totales'] as $gerencia => $item)
                <div id="project">
                    <div><span>Gerencia</span> {{$dept[$gerencia]}}</div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Cedula</th>
                            <th>Nombre y<br>Apellido</th>
                            <th>Dias<br>Asistidos</th>
                            <th>Faltas No<br>Justificadas</th>
                            <th>Permisos</th>
                            <th>Reposos</th>
                            <th>Dias de<br>Vacacion</th>
                            <th>Horas Extras<br>Diurna</th>
                            <th>Horas Extras<br>Nocturno</th>
                            <th>Bono<br>Nocturno</th>
                            <th>Guardias<br>Adicionales</th>
                            <th>Guardia<br>Sabado</th>
                            <th>Guardia<br>Domingo</th>
                            <th>Guardias<br>TOTALES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resumen['trabajador'][$gerencia] as $cedula => $nombre)
                        <tr>
                            <td style="text-align: center">
                                <?PHP echo ucwords($resumen['trab_empresa'][$cedula]);?>
                            </td>
                            <td style="text-align: center">{{$cedula}}</td>
                            <td>{{$nombre}}</td>
                            <td style="text-align: center">{{$resumen['resumen_asistencia'][$gerencia][$cedula]}}</td>
                            <td style="text-align: center">{{$resumen['resumen_faltajust'][$gerencia][$cedula]}}</td>
                            <td style="text-align: center">{{$resumen['resumen_permiso'][$gerencia][$cedula]}}</td>
                            <td style="text-align: center">{{$resumen['resumen_reposo'][$gerencia][$cedula]}}</td>
                            <td style="text-align: center">{{$resumen['resumen_vacacion'][$gerencia][$cedula]}}</td>
                            <td style="text-align: center">{{$resumen['resumen_hx_diurna'][$gerencia][$cedula]}}</td>
                            <td style="text-align: center">{{$resumen['resumen_hx_nocturna'][$gerencia][$cedula]}}</td>
                            <td style="text-align: center">{{$resumen['bono_nocturno'][$gerencia][$cedula]}}</td>
                            <td style="text-align: center">{{$resumen['adicionales'][$gerencia][$cedula]}}</td>                
                            <td style="text-align: center">{{$resumen['resumen_gd_sabado'][$gerencia][$cedula]}}</td>               
                            <td style="text-align: center">{{$resumen['resumen_gd_domingo'][$gerencia][$cedula]}}</td> 
                            <td style="text-align: center">{{$resumen['resumen_gd_totales'][$gerencia][$cedula]}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
            <div id="notices">
                <div>Nota:</div>
                <div class="notice">Acumulado quincenal por trabajador.</div>
            </div>
        </main>
        <footer>
        Uso exclusivo de la gerencia de RRHH
        </footer>
    </body>
</html>