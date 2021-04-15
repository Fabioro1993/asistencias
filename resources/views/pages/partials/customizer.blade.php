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
               <th class="center">Cedula</th>
               <th class="center">Nombre y Apellido</th>
               <th class="center">Dias Asistidos</th>
               <th class="center">Faltas No Justificadas</th>
               <th class="center">Dias de Vacacion</th>
               <th class="center">Permisos</th>
               <th class="center">Reposos</th>
               <th class="center">Horas Extras Diurna</th>
               <th class="center">Horas Extras Nocturno</th>
               <th class="center">Bono Nocturno</th>
               <th class="center">Guardia Sabado</th>
               <th class="center">Guardia Domingo</th>
               <th class="center">Guardias TOTALES</th>             
            </tr>
         </thead>
         <tbody>
            @foreach($oso as $p_oso)
            <tr>
               <td class="center">{{$p_oso->cedula}}</td>
               <td style="font-size: 11px;">{{$p_oso->nombre }}</td>                                            
               <td class="center">{{$resumen_asistencia[$p_oso->cedula]}}</td>
               <td class="center">{{$resumen_faltajust[$p_oso->cedula]}}</td>
               <td class="center">{{$resumen_vacacion[$p_oso->cedula]}}</td>
               <td class="center">{{$resumen_permiso[$p_oso->cedula]}}</td>
               <td class="center">{{$resumen_reposo[$p_oso->cedula]}}</td>
               <td class="center">{{$resumen_hx_diurna[$p_oso->cedula]}}</td>
               <td class="center">{{$resumen_hx_nocturna[$p_oso->cedula]}}</td>
               <td class="center">{{$bono_nocturno[$p_oso->cedula]}}</td>
               <td class="center">{{$resumen_gd_sabado[$p_oso->cedula]}}</td>               
               <td class="center">{{$resumen_gd_domingo[$p_oso->cedula]}}</td> 
               <td class="center">{{$resumen_gd_totales[$p_oso->cedula]}}</td>           
            </tr>
            @endforeach
         </tbody>
      </table>
   </div>
</div>
<!--/ Theme Customizer -->