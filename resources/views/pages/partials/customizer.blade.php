<!-- Theme Customizer -->

<a href="#" data-target="theme-cutomizer-out"
   class="btn btn-customizer pink accent-2 white-text sidenav-trigger theme-cutomizer-trigger">
   <i class="material-icons">settings</i></a>

<div id="theme-cutomizer-out" class="theme-cutomizer sidenav row" style="right: 12px;" >
   <div class="col s12">
      <a class="sidenav-close" href="#!"><i class="material-icons">close</i></a>
      <h5 class="theme-cutomizer-title">Resumen Quincena <?php echo date("d M/y", strtotime($fecha_max));?></h5>
      <p class="medium-small">Acumulado quincenal por trabajador</p>
      <table class="responsive-table">
         <thead>
            <tr>
               <th data-field="status">Cedula</th>
               <th data-field="status">Nombre y Apellido</th>
               <th data-field="status">Dias Asistidos</th>
               <th data-field="status">Faltas No Justificadas</th>
               <th data-field="status">Dias de Vacacion</th>
               <th data-field="status">Horas Extras Diurna</th>
               <th data-field="status">Horas Extras Nocturno</th>
               <th data-field="status">Bono Nocturno</th>
               <th data-field="status">Guardia Sabado</th>
               <th data-field="status">Guardia Domingo</th>
               <th data-field="status">Guardias TOTALES</th>             
            </tr>
         </thead>
         <tbody>
            @foreach($oso as $p_oso)
            <tr>
               <td class="center">{{$p_oso->CEDULA}}</td>
               <td style="font-size: 11px;">{{$p_oso->NOMBRE }} {{$p_oso->APELLIDO}}</td>                                            
               <td>{{$resumen_asistencia[$p_oso->CEDULA]}}</td>
               <td>{{$resumen_faltajust[$p_oso->CEDULA]}}</td>
               <td>{{$resumen_vacacion[$p_oso->CEDULA]}}</td>
               <td>{{$resumen_hx_diurna[$p_oso->CEDULA]}}</td>
               <td>{{$resumen_hx_nocturna[$p_oso->CEDULA]}}</td>
               <td>{{$bono_nocturno[$p_oso->CEDULA]}}</td>
               <td>{{$resumen_gd_sabado[$p_oso->CEDULA]}}</td>               
               <td>{{$resumen_gd_domingo[$p_oso->CEDULA]}}</td> 
               <td>{{$resumen_gd_totales[$p_oso->CEDULA]}}</td>           
            </tr>
            @endforeach
         </tbody>
      </table>
   </div>
</div>
<!--/ Theme Customizer -->