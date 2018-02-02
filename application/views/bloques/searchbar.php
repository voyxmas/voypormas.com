<form id="main_search" action="<?php echo base_url().'app/home' ?>" method="get">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input id="name" name="nombre" type="text" class="selectpicker form-control">
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <select id="evento_tipo_id" name="evento_tipo_id" class="selectpicker form-control" data-live-search="true">
                            <option value="-1">Todos</option>
                            <?php foreach($categorias as $segmento => $categoria_items): ?>
                            <optgroup label="<?php echo $segmento ?>">
                                <?php foreach ($categoria_items AS $key => $categoria ) : ?>
                                <option value="<?php echo $key ?>"><?php echo $categoria ?></option>
                                <?php endforeach ?>
                            </optgroup>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fecha</label>
                        <input id="fecha" name="fecha" type="date" class="date-picker form-control">
                    </div>
                    <div class="form-group">
                        <label>Distancia</label>
                        <div>
                            <input name="distancia1" type="hidden" >
                            <div id="distancia1" class="input col-xs-3"></div>
                            <div data-min="<?php echo $distancialimits['distancia_min'] ?>" data-mininput="distancia1" data-max="<?php echo $distancialimits['distancia_max'] ?>" data-maxinput="distancia2" data-name="distancia" data-sufix="km" class='col-xs-6 nouislider'></div>
                            <input name="distancia2" type="hidden" >
                            <div id="distancia2" class="input col-xs-3"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Lugar</label>
                        <input id="lugar" name="lugar" class="form-control">
                        <input type="hidden" id="numero_casa" name="numero_casa" class="form-control">
                        <input type="hidden" id="calle" name="calle" class="form-control">
                        <input type="hidden" id="ciudad" name="ciudad" class="form-control">
                        <input type="hidden" id="departamento" name="departamento" class="form-control">
                        <input type="hidden" id="provincia" name="provincia" class="form-control">
                        <input type="hidden" id="pais" name="pais" class="form-control">
                        <input type="hidden" id="latitud" name="latitud" class="form-control">
                        <input type="hidden" id="longitud" name="longitud" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Precio</label>
                        <div>
                            <input name="precio1" name="precio1" type="hidden" >
                            <div id="precio1" class="input col-xs-3"></div>
                            <div precio data-min="<?php echo $pricelimits['precio_min'] ?>" data-minInput="precio1" data-max="<?php echo $pricelimits['precio_max'] ?>" data-maxInput="precio2" data-name="precio" data-prefix="$" class='col-xs-6 nouislider'></div>
                            <input name="precio2" type="hidden" >
                            <div id="precio2" class="input col-xs-3"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="col-xs-12 btn btn-square todo-bold">Buscar</button>
                        </div>
                    </div>
                </form>