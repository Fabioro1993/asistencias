<div>
    <div class="col s12">
        <a class="sidenav-close" href="#!"><i class="material-icons">close</i></a>
        <h5 class="theme-cutomizer-title">Resumen Quincena <?php echo date("d M/y", strtotime($resumen_edit['fecha_max']));?></h5>
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
            @foreach($oso as $det)
            <tr>
               <td class="center">{{$det->CEDULA}}</td>
               <td style="font-size: 11px;">{{$det->NOMBRE}}</td> 
               <td class="center">{{$resumen_edit['resumen_asistencia'][$det->CEDULA]}}</td>
               <td class="center">{{$resumen_edit['resumen_faltajust'][$det->CEDULA]}}</td>
               <td class="center">{{$resumen_edit['resumen_vacacion'][$det->CEDULA]}}</td>
               <td class="center">{{$resumen_edit['resumen_hx_diurna'][$det->CEDULA]}}</td>
               <td class="center">{{$resumen_edit['resumen_hx_nocturna'][$det->CEDULA]}}</td>
               <td class="center">{{$resumen_edit['bono_nocturno'][$det->CEDULA]}}</td>
               <td class="center">{{$resumen_edit['adicionales'][$det->CEDULA]}}</td>                
               <td class="center">{{$resumen_edit['resumen_gd_sabado'][$det->CEDULA]}}</td>               
               <td class="center">{{$resumen_edit['resumen_gd_domingo'][$det->CEDULA]}}</td> 
               <td class="center">{{$resumen_edit['resumen_gd_totales'][$det->CEDULA]}}</td>           
            </tr>
            @endforeach
           </tbody>
        </table>
     </div>
</div>
