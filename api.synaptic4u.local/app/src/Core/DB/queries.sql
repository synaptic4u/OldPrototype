
CREATE DEFINER=`api`@`localhost` PROCEDURE `api`.`GetHierachy`(IN `searchid` INT, `query` INT)
BEGIN
	declare temphierachyid INTEGER DEFAULT 0;
	declare temphierachysubid INTEGER DEFAULT 0;
	declare tempid INTEGER DEFAULT 0;
	declare conditionvar  INTEGER default 1;
    declare finished INTEGER DEFAULT 0;
    declare levelid INTEGER default 0;
  
	declare curhierachy 
		CURSOR FOR 
			SELECT hu.hierachyid, h.hierachysubid 
        FROM hierachy_users hu
        JOIN hierachy h
          ON hu.hierachyid = h.hierachyid
       where hu.userid = searchid order by hu.hierachyid DESC;

	declare CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;

  create temporary table get_hierachy(hierachyid INTEGER, hierachysubid INTEGER);
  create temporary table final_hierachy(hierachyid INTEGER, hierachysubid INTEGER);
 
	OPEN curhierachy;

	gethierachy: LOOP
		FETCH curhierachy INTO temphierachyid, temphierachysubid;
		IF finished = 1 THEN 
			LEAVE gethierachy;
		END IF;
		
      insert into get_hierachy(hierachyid, hierachysubid)values(temphierachyid, temphierachysubid);
       
      set tempid = temphierachysubid;
       
      WHILE conditionvar <> 0 DO

        if tempid = 0 
          then
            SET conditionvar = 0;
          else
        	
				  replace INTO get_hierachy 
			 	   select hierachyid, hierachysubid
				     from hierachy
				    where hierachyid = tempid;	 
				
		          set tempid = (select hierachysubid from hierachy where hierachyid = tempid);
			  end if;
       
 	    END WHILE;

	END LOOP gethierachy;
	CLOSE curhierachy;

  insert into final_hierachy 
  select *
    from get_hierachy 
   group by hierachyid 
   order by hierachysubid;

  drop temporary table get_hierachy;
 
 if query = 1
 then
  select f.hierachyid, f.hierachysubid, hd.name, "default" as logo
    from final_hierachy f
    join hierachy_det hd 
      on f.hierachyid = hd.hierachyid 
    left join hierachy_particulars hp
      on hd.detid = hp.detid
    left join hierachy_images hi
      on hp.particularid = hi.particularid
   order by f.hierachysubid, f.hierachyid;
 end if;
 if query = 2
 then
  select f.hierachyid, f.hierachysubid, h.levelid, hd.name, h.visible
    from final_hierachy f
    join hierachy h
      on f.hierachyid = h.hierachyid 
    join hierachy_det hd 
      on f.hierachyid = hd.hierachyid 
   order by f.hierachysubid, f.hierachyid;
 end if;

  drop temporary table final_hierachy;

END