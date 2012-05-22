
<h1>Ajouter une collection de données</h1>

<form action="/collections/ajouter.html?next=<?=rawurlencode(NE($_REQUEST,'next'))?>" class="login" method="post">
    
    <?php if($this->hasErrors()) { ?>
    <div class="error">
    <p>Votre formulaire contient des erreurs:</p>
	<?=$this->displayErrors(true,array('alwaysList'=>true))?>
    </div>
    <?php } ?>
    
    <div class="field">
    	<label>Nom :</label>
        <input type="text" name="name" class="text" value="<?=NE($_POST,'name')?>" />
        <div class="clear"></div>
    </div>
    
    <div class="field">
    	<label>Description :</label>
        <textarea name="description"><?=NE($_POST,'description')?></textarea>
        <div class="clear"></div>
    </div>
	
    <div class="spacer-small"></div>
    <div class="hr"></div>
    <div class="spacer-small"></div>
    
    <div id="source">
    
    	<h4>Source des données</h4>
    
        <div class="spacer-small"></div>
    
    	<div class="selectForm">
        
            <div class="fleft mr20">
                <h5>Type de données à importer :</h5>
                <div class="field types">
                    <div class="mt10">
                        <div class="mt5">
                            <label class="neutral">
                                <input type="radio" name="type" value="csv" <?=(NE($_POST,'type') == 'csv' ? 'checked="checked"':'')?> /> 
                                CSV
                            </label>
                        </div>
                        <div class="mt5">
                            <label class="neutral">
                                <input type="radio" name="type" value="excel" <?=(NE($_POST,'type') == 'excel' ? 'checked="checked"':'')?> /> 
                                Excel
                            </label>
                        </div>
                        <div class="mt5">
                            <label class="neutral">
                                <input type="radio" name="type" value="mysql" <?=(NE($_POST,'type') == 'mysql' ? 'checked="checked"':'')?> /> 
                                MySQL
                            </label>
                        </div>
                        <div class="mt5">
                            <label class="neutral">
                                <input type="radio" name="type" value="html" <?=(NE($_POST,'type') == 'html' ? 'checked="checked"':'')?> /> 
                                HTML
                            </label>
                        </div>
                        <div class="mt5">
                            <label class="neutral">
                                <input type="radio" name="type" value="json" <?=(NE($_POST,'type') == 'json' ? 'checked="checked"':'')?> /> 
                                JSON
                            </label>
                        </div>
                        <div class="mt5">
                            <label class="neutral">
                                <input type="radio" name="type" value="xml" <?=(NE($_POST,'type') == 'xml' ? 'checked="checked"':'')?> /> 
                                XML
                            </label>
                        </div>
                        <div class="mt5">
                            <label class="neutral">
                                <input type="radio" name="type" value="rss" <?=(NE($_POST,'type') == 'rss' ? 'checked="checked"':'')?> /> 
                                RSS
                            </label>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            
            <div class="fleft ml20">
                <div class="form file" style="display:none;">
                    <h5>Fichier de données :</h5>
                    <div class="field">
                        <label>Sélectionnez le fichier de données sur votre ordinateur :</label>
                        <input type="file" name="file" />
                        <div class="clear"></div>
                    </div>
                    <div class="field mt20 mb20"><strong>OU</strong></div>
                    <div class="field">
                        <label>Entrez l'url du fichier de données :</label>
                        <input type="text" name="url" class="text" value="<?=NE($_POST,'url')?>" />
                        <div class="clear"></div>
                    </div>
                    <div class="spacer-small"></div>
                    <div class="fetch">
                        <button type="button">Charger le fichier de données</button>
                    </div>
                </div>
                <div class="form mysql" style="display:none;">
                    <h5>Connexion mySQL :</h5>
                    <div class="field fleft mr20">
                        <label>Adresse du serveur mySQL :</label>
                        <input type="text" name="mysql_host" class="text" value="<?=NE($_POST,'mysql_host')?>" style="width:240px;" />
                        <div class="clear"></div>
                    </div>
                    <div class="field fleft">
                        <label>Port :</label>
                        <input type="text" name="mysql_port" class="text number" value="<?=NE($_POST,'mysql_port',3306)?>" />
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <div class="field">
                        <label>Nom d'utilisateur :</label>
                        <input type="text" name="mysql_username" class="text" value="<?=NE($_POST,'mysql_username')?>" />
                        <div class="clear"></div>
                    </div>
                    <div class="field">
                        <label>Mot de passe :</label>
                        <input type="password" name="mysql_password" class="text" value="<?=NE($_POST,'mysql_password')?>" />
                        <div class="clear"></div>
                    </div>
                    <div class="field">
                        <label>Nom de la base de données :</label>
                        <input type="password" name="mysql_database" class="text" value="<?=NE($_POST,'mysql_database')?>" />
                        <div class="clear"></div>
                    </div>
                    <div class="field">
                        <div class="option">
                            <label class="neutral">
                                <input type="radio" name="mysql_method" value="table" <?=(NE($_POST,'mysql_method','table') == 'table' ? 'checked="checked"':'')?> /> 
                                 Nom de la table
                            </label>
                            <div class="input mt5" style="display:none;">
                                <input type="text" name="mysql_table" class="text" value="<?=NE($_POST,'mysql_table')?>" />
                            </div>
                        </div>
                        <div class="option mt10">
                            <label class="neutral">
                                <input type="radio" name="mysql_method" value="sql" <?=(NE($_POST,'mysql_method') == 'sql' ? 'checked="checked"':'')?> /> 
                                 Requête SQL personnalisée
                            </label>
                            <div class="input mt5" style="display:none;">
                                <textarea name="mysql_sql"><?=NE($_POST,'mysql_sql')?></textarea>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="spacer-small"></div>
                    <div class="fetch">
                        <button type="button">Se connecter à la base de données</button>
                    </div>
                </div>
            </div>
        
        	<div class="clear"></div>
            
        </div>
        
        <div class="selectedItem" style="display:none;">
        	<div class="ui-state-active ui-corner-all">
            	<div class="links fright">
                    <a href="#" class="change">Modifier la source de données</a>
                </div>
                <div class="label"><a href="#">&nbsp;</a></div>
                <div class="clear"></div>
            </div>
            
            <div class="dataRaw" style="display:none;">
            	<div class="spacer-small"></div>
            	<h5>Données brutes</h5>
                <div class="inner">
                
                </div>
            </div>
            
            <div class="options csv" style="display:none;">
            	<div class="spacer-small"></div>
            	<h5>Options CSV</h5>
                <div class="inner">
                	<div class="field">
                        <label>Séparateur de colonne :</label>
                        <input type="text" class="separator" class="text number" value="," />
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            
            <div class="options html" style="display:none;">
            	<div class="spacer-small"></div>
            	<h5>Options HTML</h5>
                <div class="inner">
                	<div class="field">
                        <label>Code pour obtenir les données :</label>
                        <div class="editor">
                        	<pre id="htmlParserEditor"></pre>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            
            
            
            
            
        </div>
        
    
    </div>
    
    <div class="spacer-small"></div>
    <div class="hr"></div>
    <div class="spacer-small"></div>
    
    
    <div id="schema">
    	<h4>Schéma</h4>
    	
        <div class="viewer">
            <div class="cols">
            	<div class="title">Colonnes</div>
                <div class="inner">
                	<div class="list"></div>
                	<?php if(isset($cols) && sizeof($cols)) { ?>
                	<input type="hidden" class="cols" value="<?=rawurlencode(json_encode($cols))?>" />
                    <?php } ?>
                    <div class="tmpl">
                    	<h3><a href="#"></a></h3>
                        <div>
                        	<div class="field ">
                                <label>Clé :</label>
                                <input type="text" name="cols_key[]" class="text" value="" />
                                <div class="note">Lettres, chiffres et _ seulement.</div>
                                <div class="clear"></div>
                            </div>
                            
                            <div class="field">
                                <label>Description :</label>
                                <textarea name="cols_description[]"></textarea>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="data">
            	<div class="title">Données interprétées</div>
                <div class="inner">
                
                </div>
            </div>
            <div class="clear"></div>
        </div>
        
    </div>
    
    <div class="spacer-small"></div>
    <div class="hr"></div>
    <div class="spacer-small"></div>
    
    <div class="buttons" align="right">
    	<a href="<?=NE($_REQUEST,'next','/collections')?>">Annuler</a> 
    	<button type="submit" class="disabled" disabled="disabled">Ajouter la collection de données &raquo;</button>
    </div>
</form>