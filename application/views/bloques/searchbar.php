<form id="main_search" action="<?php echo base_url().'app/home' ?>" method="get">
                    <div class="form-group">
                        <label>Nombre </label>
                        <input id="name" name="nombre" type="text" class="selectpicker form-control" value="<?php echo $this->input->get('nombre') ?>">
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                            <select id="evento_tipo_id" name="evento_tipo_id" class="selectpicker form-control" data-live-search="true">
                                <option value="-1">Todos</option>
                                <?php foreach($categorias as $segmento => $categoria_items): ?>
                                <optgroup label="<?php echo $segmento ?>">
                                    <?php foreach ($categoria_items AS $key => $categoria ) : ?>
                                    <option <?php echo $this->input->get('evento_tipo_id') == $key ? 'selected' : NULL ?> value="<?php echo $key ?>"><?php echo $categoria ?></option>
                                    <?php endforeach ?>
                                </optgroup>
                                <?php endforeach ?>
                            </select>
                    </div>
                    <div class="form-group col-xs-6">
                        <label>Lugar</label>
                            <input id="lugar" name="lugar" class="form-control" value="<?php echo $this->input->get('lugar') ?>">
                            <input type="hidden" id="numero_casa" name="numero_casa" class="form-control" value="<?php echo $this->input->get('numero_casa') ?>">
                            <input type="hidden" id="calle" name="calle" class="form-control" value="<?php echo $this->input->get('calle') ?>">
                            <input type="hidden" id="ciudad" name="ciudad" class="form-control" value="<?php echo $this->input->get('ciudad') ?>">
                            <input type="hidden" id="departamento" name="departamento" class="form-control" value="<?php echo $this->input->get('departamento') ?>">
                            <input type="hidden" id="provincia" name="provincia" class="form-control" value="<?php echo $this->input->get('provincia') ?>">
                            <input type="hidden" id="pais" name="pais" class="form-control" value="<?php echo $this->input->get('pais') ?>">
                            <input type="hidden" id="latitud" name="latitud" class="form-control" value="<?php echo $this->input->get('latitud') ?>">
                            <input type="hidden" id="longitud" name="longitud" class="form-control" value="<?php echo $this->input->get('longitud') ?>">
                    </div>
                    <div class="form-group col-xs-6">
                        <label>Fecha</label>
                        <input id="fecha" name="fecha" type="text" autocomplete="off" class="date-picker form-control" value="<?php echo $this->input->get('fecha') ?>">
                    </div>
                    <div class="form-group col-md-6 col-xs-12">
                        <label>Distancia</label>
                        <div>
                            <input name="distancia1" type="hidden" >
                            <div id="distancia1" class="input col-xs-3"></div>
                            <div 
                                data-min="<?php echo $distancialimits['distancia_min'] ?>" 
                                data-mininput="distancia1" 
                                data-max="<?php echo $distancialimits['distancia_max'] ?>" 
                                data-maxinput="distancia2" 
                                data-minselected="<?php echo !empty($this->input->get('distancia1')) ? $this->input->get('distancia1') : $distancialimits['distancia_min'] ?>"
                                data-maxselected="<?php echo !empty($this->input->get('distancia2')) ? $this->input->get('distancia2') : $distancialimits['distancia_max'] ?>" 
                                data-name="distancia" 
                                data-sufix="km" 
                                class='col-xs-6 nouislider'>
                            </div>
                            <input name="distancia2" type="hidden" >
                            <div id="distancia2" class="input col-xs-3"></div>
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-xs-12">
                        <label>Precio</label>
                        <div>
                            <input name="precio1" name="precio1" type="hidden" >
                            <div id="precio1" class="input col-xs-3"></div>
                            <div 
                                data-min="<?php echo $pricelimits['monto_min'] ?>" 
                                data-minInput="precio1" 
                                data-max="<?php echo $pricelimits['monto_max'] ?>" 
                                data-maxInput="precio2" 
                                data-minselected="<?php echo !empty($this->input->get('precio1')) ? $this->input->get('precio1') : $pricelimits['monto_min'] ?>" 
                                data-maxselected="<?php echo !empty($this->input->get('precio2')) ? $this->input->get('precio2') : $pricelimits['monto_max'] ?>" 
                                data-name="precio" 
                                data-prefix="$" 
                                class='col-xs-6 nouislider'>
                            </div>
                            <input name="precio2" type="hidden" >
                            <div id="precio2" class="input col-xs-3"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="hidden-md-down">&nbsp;</label>
                        <div>
                            <button type="submit" class="col-xs-12 btn btn-square btn-info todo-bold">Buscar!</button>
                        </div>
                        <p class="hidden-md-up mas-filtros-mejor">Mientras más filtros uses, mejor será tu resultado</p>
                    </div>
                </form>