@section('title','Resumen nomina')
<div wire:ignore.self>
    <div class="card-panel">
        <div class="row">
            <div class="col s12 m7">
                <div class="display-flex media">
                    <div class="media-body">
                        <h5 class="theme-cutomizer-title">Resumen Quincena <?php echo date("d M/y", strtotime($resumen['fecha_max']));?></h5>
                        <p class="caption mb-0">Acumulado quincenal por trabajador</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m5 quick-action-btns display-flex justify-content-end align-items-center pt-2">
                <a href="{{url('nomina/resumen/pdf/'.$quincena.'/'.$mes.'/'.$anio)}}" target="_blank" class="btn-small indigo">PDF</a>
            </div>                
        </div>
    </div>
    @foreach ($resumen['resumen_gd_totales'] as $gerencia => $item)
        <div class="row">
            <div class="col s12">
                <div id="borderless-table" class="card card-tabs">
                    <div class="card-content">
                        <h4 class="card-title">{{$dept[$gerencia]}}</h4>
                        <div id="view-borderless-table">
                            <div class="row">
                                <div class="col s12">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="center">Empresa</th>
                                                <th class="center">Cedula</th>
                                                <th class="center">Nombre y Apellido</th>
                                                <th class="center">Dias Asistidos</th>
                                                <th class="center">Faltas No Justificadas</th>
                                                <th class="center">Permisos</th>
                                                <th class="center">Reposos</th>
                                                <th class="center">Dias de Vacacion</th>
                                                <th class="center">Horas Extras Diurna</th>
                                                <th class="center">Horas Extras Nocturno</th>
                                                <th class="center">Bono Nocturno</th>
                                                <th class="center">Guardias Adicionales</th>
                                                <th class="center">Guardia Sabado</th>
                                                <th class="center">Guardia Domingo</th>
                                                <th class="center">Guardias TOTALES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($resumen['trabajador'][$gerencia] as $cedula => $nombre)
                                            <tr>
                                                <td class="center">
                                                    <?PHP echo ucwords($resumen['trab_empresa'][$cedula]);?>
                                                </td>
                                                <td>{{$cedula}}</td>
                                                <td style="font-size: 11px;">{{$nombre}}</td>
                                                <td class="center">{{$resumen['resumen_asistencia'][$gerencia][$cedula]}}</td>
                                                <td class="center">{{$resumen['resumen_faltajust'][$gerencia][$cedula]}}</td>
                                                <td class="center">{{$resumen['resumen_permiso'][$gerencia][$cedula]}}</td>
                                                <td class="center">{{$resumen['resumen_reposo'][$gerencia][$cedula]}}</td>
                                                <td class="center">{{$resumen['resumen_vacacion'][$gerencia][$cedula]}}</td>
                                                <td class="center">{{$resumen['resumen_hx_diurna'][$gerencia][$cedula]}}</td>
                                                <td class="center">{{$resumen['resumen_hx_nocturna'][$gerencia][$cedula]}}</td>
                                                <td class="center">{{$resumen['bono_nocturno'][$gerencia][$cedula]}}</td>
                                                <td class="center">{{$resumen['adicionales'][$gerencia][$cedula]}}</td>                
                                                <td class="center">{{$resumen['resumen_gd_sabado'][$gerencia][$cedula]}}</td>               
                                                <td class="center">{{$resumen['resumen_gd_domingo'][$gerencia][$cedula]}}</td> 
                                                <td class="center">{{$resumen['resumen_gd_totales'][$gerencia][$cedula]}}</td>
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
        </div>
    @endforeach
</div>