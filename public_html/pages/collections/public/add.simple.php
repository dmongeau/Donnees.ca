
<h1>Ajouter des données</h1>
    
<?php if($this->hasErrors()) { ?>
<div class="error">
<p>Votre formulaire contient des erreurs:</p>
<?=$this->displayErrors(true,array('alwaysList'=>true))?>
</div>
<?php } ?>

<!--
	
	Import data
	
-->
<h2>1. Importer une source de données</h2>

<div id="import" class="source-hide">
		
	<ul>
		<li><a href="#file">Fichier de données</a></li>
		<li><a href="#mysql">MySQL</a></li>
	</ul>
	<div id="file">
		<form action="/fetch/file" method="post" enctype="multipart/form-data" target="fileFrame">
			<div class="field">
				<label>Formats supportés :</label>
				<div>CSV, XML, JSON, Excel, TXT</div>
			</div>
			<div class="spacer-small"></div>
			<div class="hr"></div>
		
			<div class="col col-first">
				<div class="field">
					<label>Sélectionnez un fichier sur votre ordinateur :</label>
					<input type="file" name="file" />
				</div>
			</div>
			<div class="col">
				<div class="field">
					<label>Entrez l'url du fichier :</label>
					<input type="text" name="url" class="text" />
				</div>
			</div>
			<div class="clear"></div>
			
			<div class="hr"></div>
			<div class="spacer-small"></div>
			<div align="center">
				<button type="submit">Importer le fichier de données</button>
			</div>
            
		</form>
		<iframe id="fileFrame" name="fileFrame" src="about:blank" width="1" height="1" frameborder="0" style="visibility:hidden;"></iframe>
	</div>
	<div id="mysql">
		<form action="/fetch/mysql" method="post" enctype="multipart/form-data" target="mysqlFrame">
			
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
			
				<div class="hr"></div>
				<div class="spacer-small"></div>
				<div align="center">
					<button type="submit">Importer les données</button>
				</div>
			</div>
			
		</form>
		<iframe id="mysqlFrame" name="mysqlFrame" src="about:blank" width="1" height="1" frameborder="0" style="visibility:hidden;"></iframe>
	</div>
</div>	

<!--
	
	Data source
	
-->	
<div id="source" class="source-show" style="display:none">
	<div class="selected">
		<div class="icons">
			<a href="#" class="button edit">Modifier la source de données</a>
		</div>
		<div class="label">
			<strong class="type"></strong> 
			<span class="name"></span>
		</div>
		<div class="infos">
			Taille: <span class="size"></span>
		</div>
	</div>
	<div class="rawData">
		<div class="editor"><pre id="editorRaw"></pre></div>
	</div>
</div>
 	
 

<div class="spacer-small"></div>    
<div class="hr"></div>             
<div class="spacer-small"></div>   
 

<form action="/collections/ajouter.html?next=<?=rawurlencode(NE($_REQUEST,'next'))?>" class="login" method="post" enctype="multipart/form-data"> 
	
	<!--
	
		Organize data
		
	-->
	<div id="organize">
		<h2>2. Organiser les données</h2>

		<div class="waiting source-hide">
			Vous devez d'abord importer une source de données ci-dessus...
		</div>

		<div class="source-show" style="display:none">
			<!--
				Options
			-->
			<div id="options">
				<h3>Interpréter les données</h3>
				<div class="formats">
					<ul>
						<?php foreach($this->data['formats'] as $key => $format) { ?>
						<li><a href="#format-<?=$key?>"><?=$format['name']?></a></li>
						<?php } ?>
					</ul>
					
					<?php foreach($this->data['formats'] as $key => $format) { ?>
						
						<!-- <?=$format['name']?> -->
						<div id="format-<?=$key?>" class="format">
							<?php
								if(file_exists(PATH_PAGES.'/_helpers/formats/'.$key.'.options.php')) {
									include PATH_PAGES.'/_helpers/formats/'.$key.'.options.php';
								}
							?>
						</div>
						
					<?php } ?>
				</div>
			</div>
			
			<!--
				Result
			-->
			<div id="result">
				<h3>Données interprétées</h3>
				<div class="waiting">
					Le format de données n'est pas reconnu, veuillez utiliser le section de gauche pour décoder les données.
				</div>
				<div class="item" style="display:none;">
					<ul>
						<li><a href="#result-rows">Items</a></li>
						<li><a href="#result-cols">Colonnes</a></li>
					</ul>
					<div id="result-rows">
                        <div class="field">
                        	<label>Nombre d'items :</label>
                            <div class="fleft total"></div>
                            <div class="clear"></div>
                        </div>
						<div class="hr"></div>
						<!--<div class="list">-->
                       	 	<ul>
								<li class="tmpl">
									<div class="name"></div>
									<div class="header">
										<label class="key">Colonne</label>
										<div class="value">Valeur</div>
										<div class="clear"></div>
									</div>
									<div class="col">
										<label class="key"></label>
										<div class="value"></div>
										<div class="clear"></div>
									</div>
								</li>
							</ul>
						<!--</div>-->
					</div>
					<div id="result-cols">
                        <div class="field">
                        	<label>Nombre de colonnes :</label>
                            <div class="fleft total"></div>
                            <div class="clear"></div>
                        </div>
						<div class="hr"></div>
						<!--<div class="list">-->
                            <div class="header">
                                <div class="name">Nom de la colonne</div>
                                <div class="description">Description</div>
                                <div class="clear"></div>
                            </div>
		                    <ul>
		                        <li class="tmpl">
		                            <div class="name">
		                                <input type="text" name="cols_name[]" class="text" />
		                            </div>
		                            <div class="description">
		                                <textarea name="cols_description[]"></textarea>
		                            </div>
                                    <div class="clear"></div>
		                        </li>
		                    </ul>
                       <!-- </div>-->
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
    
    <div class="spacer-small"></div>
    <div class="hr"></div>
    <div class="spacer-small"></div>

	<!--
	
		Confirm data
		
	-->
	<div id="confirm">
		
		<input type="hidden" name="data" />
		
		<h2>3. Résumé des données à importer</h2>
		<div class="waiting source-hide">
			Vous devez d'abord importer une source de données ci-dessus et l'organiser...
		</div>
		<div class="" style="display:none;">
			<div class="infos">
				<div class="field">
					<label>Nombre de rangées :</label>
					<div class="rowsCount"></div>
					<div class="clear"></div>
				</div>
				<div class="field">
					<label>Nombre de colonnes :</label>
					<div class="columnsCount"></div>
					<div class="clear"></div>
				</div>
				<div class="field">
					<label>Colonnes :</label>
					<div class="columns">
						<ul></ul>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="data">
				<ul></ul>
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


<script type="text/javascript">
	
	window.FORMATS_SUPPORTED = <?=json_encode($this->data['formats'])?>;

</script>