<div>
    <div class="col s12">
        <a class="sidenav-close" href="#!"><i class="material-icons">close</i></a>
        <h5 class="theme-cutomizer-title">Resumen Quincena <?php echo date("d M/y", strtotime($fecha_max));?></h5>
        <p class="medium-small">Acumulado quincenal por trabajador</p>
        <table class="responsive-table">
           <thead>
              <tr>
                 <th data-field="status" class="center">Cedula</th>
                 <th data-field="status" class="center">Nombre y Apellido</th>
                 <th data-field="status" class="center">Dias Asistidos</th>
                 <th data-field="status" class="center">Faltas No Justificadas</th>
                 <th data-field="status" class="center">Dias de Vacacion</th>
                 <th data-field="status" class="center">Horas Extras Diurna</th>
                 <th data-field="status" class="center">Horas Extras Nocturno</th>
                 <th data-field="status" class="center">Bono Nocturno</th>
                 <th data-field="status" class="center">Guardias Adicionales</th>
                 <th data-field="status" class="center">Guardia Sabado</th>
                 <th data-field="status" class="center">Guardia Domingo</th>
                 <th data-field="status" class="center">Guardias TOTALES</th>             
              </tr>
           </thead>
           <tbody>
            @foreach($registro_cab->registro_det as $det)
            <tr>
               <td class="center">{{$det->cedula}}</td>
               <td style="font-size: 11px;">{{$det->nombre}}</td> 
               <td class="center">{{$resumen_asistencia[$det->cedula]}}</td>
               <td class="center">{{$resumen_faltajust[$det->cedula]}}</td>
               <td class="center">{{$resumen_vacacion[$det->cedula]}}</td>
               <td class="center">{{$resumen_hx_diurna[$det->cedula]}}</td>
               <td class="center">{{$resumen_hx_nocturna[$det->cedula]}}</td>
               <td class="center">{{$bono_nocturno[$det->cedula]}}</td>
               <td class="center">{{$adicionales[$det->cedula]}}</td>                
               <td class="center">{{$resumen_gd_sabado[$det->cedula]}}</td>               
               <td class="center">{{$resumen_gd_domingo[$det->cedula]}}</td> 
               <td class="center">{{$resumen_gd_totales[$det->cedula]}}</td>           
            </tr>
            @endforeach
           </tbody>
        </table>
     </div>
</div>
