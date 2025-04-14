<?php
/*
* isInBounds.php
* Andrew Leamy, April 2025
* File contains a function which determines if a given clinician is in 
* or out of bounds.
*/

//determines if the passed geoJSON data represents a clinician who is
//in or out of bounds. Returns 1 if clinician is in bounds, 0 if
//clinician is out of bounds, and -1 if there was an error.
function isInBounds($geoJSON)
{
        //parse geoJSON data into object
        $geoData = json_decode($geoJSON);
        $clinicianCoords = [];
        $polygonCoords = [];
        $inBBox = false;
    
        //Note that this script assumes that the GeoJSON data always contains exactly 
        //one point and one polygon.
        foreach($geoData->features as $geoFeature)
        {
            if ($geoFeature->geometry->type == "Point")
            {
                $clinicianCoords = $geoFeature->geometry->coordinates;
            }
            else if ($geoFeature->geometry->type == "Polygon")
            {
                $polygonCoords = $geoFeature->geometry->coordinates[0];
            }
        }
    
        //error handling if api call returns invalid data
        if (empty($polygonCoords) || empty($clinicianCoords))
        {
            return -1;
        }
    
        //Mathematically determine if the clinician's coordinates are inside
        //of the polygon boundry.
    
        //first define a bounding box by the points of the polygon that are
        //furthest in each direction. max/min left max/min up etc.
        //can first check if point is in bounding box by comparing value of coords.
        //would need to check for edge case if bounding box crosses the meridians?
        //after checking bounding box, could then check actual polygon using ray tracing algorithm.
        $maxNorth = PHP_INT_MIN;
        $maxEast = PHP_INT_MIN;
        $maxSouth = PHP_INT_MAX;
        $maxWest = PHP_INT_MAX;
        foreach ($polygonCoords as $vertex)
        {
            //note that a greater x coordinate is further east, and a greater y coordinate is further north
            if ($vertex[0] > $maxEast) {$maxEast = $vertex[0];}
            else if ($vertex[0] < $maxWest) {$maxWest = $vertex[0];}
            if ($vertex[1] > $maxNorth) {$maxNorth = $vertex[1];}
            else if ($vertex[1] < $maxSouth) {$maxSouth = $vertex[1];}
    
            //Please note that there is no error handeling for the edge case where the bounding box
            //crosses over either the antimeridian or the earth's poles.        
        }
    
        //determine if clinician is inside of bounding box.
        $inBounds = true;
        if ($clinicianCoords[0] > $maxEast
            || $clinicianCoords[0] < $maxWest
            || $clinicianCoords[1] > $maxNorth
            || $clinicianCoords[1] < $maxSouth)
        {
            $inBounds = false;
        }
    
    
        //If clinician is within bounding box, now determine if clinician is within boundry polygon
        //by using a ray-casting algorithm. Mathematically, if a horizontal ray starting at the 
        //clinician's coordinates intersects the polygon an odd number of times, then
        //it is within the polygon, and outside otherwise.
        if($inBounds)
        {
            $inBBox = true;
            //First, define a ray traveling due east from the clinician's coords to the east edge of the boundry box.
            $ray = array("x0" => $clinicianCoords[0], "y0" => $clinicianCoords[1],
                            "x1" => $maxEast, "y1" => $clinicianCoords[1]);
    
            $intersectionCount = 0;
    
            //now for each edge of the boundry polygon, determine if the ray has an intersection point with it.
            for($i = 0; $i < count($polygonCoords) - 1; $i++)
            {
                //define the current edge as the line between the current vertex point of the polygon and the next one.
                $edge = array("x0" => $polygonCoords[$i][0], "y0" => $polygonCoords[$i][1],
                                "x1" => $polygonCoords[$i+1][0], "y1" => $polygonCoords[$i+1][1]);
    
                //First check if edge is horizontal, such as when the polygon is a square.
                //if so then it can only intersect the ray at its y-coordinate
                if ($edge["y0"] == $edge["y1"])
                {
                    if ($ray["y0"] == $edge["y0"])
                    {
                        $intersectionCount++;
                    }
                    continue;
                }
    
                //next check if the ray is within the verticle bounds of the edge.
                //if not, then becasue the ray is horizontal the edge must be either 
                //entirely above or below the clinician and thus cannot intersect with the ray.
                if($ray["y0"] >= min($edge["y0"], $edge["y1"])
                    && $ray["y0"] <= max($edge["y0"], $edge["y1"]))
                {
                    //next, apply the same logic to the horizontal bounds of the edge.
                    //we only need to check if the edge is further east than the ray's
                    //startpoint since we know the ray extends to the easternmost bound.
                    if ($ray["x0"] <= max($edge["x0"], $edge["x1"]))
                    {
                        //since the edge is at least partially east of the clinician, 
                        //there *must* be an intersection if the edge is verticle. 
                        if ($edge["x0"] == $edge["x1"])
                        {
                            $intersectionCount++;
                            continue;
                        }
    
                        //at this point, we know there is a horizontal line intersection
                        //with the clinician's x-coordinate and the edge. We need to test 
                        //if that intersection is on our ray to see if it counts for our 
                        //purposes or not.
                        //I won't give the full explanation here, but the x coordinate of
                        //this intersection can be found via vector calculus with the following
                        //formula. if the result is east of the clinician, then we have a ray intersection.
                        $xIntersection = ($ray["y0"] - $edge["y0"]) * ($edge["x1"] - $edge["x0"]) / ($edge["y1"] - $edge["y0"]) + $edge["x0"];
                        if ($ray["x0"] <= $xIntersection)
                        {
                            $intersectionCount++;
                        }
                    }
                }
            }
    
            //now that we have tested each edge in the polygon for ray intersection, 
            //we can determine if the clinician is inside the polygon by seeing if
            //there was an ODD number of intersections. (0 counts as even for this.)
            if ($intersectionCount % 2 == 0)
            {
                $inBounds = false;
            }
            else
            {
                $inBounds = true;
            }
        }
    
        //return wether or not the clincian is in bounds.
        // if ($inBounds)
        // {
        //     $returnMessage = "<p style='color:green'>WITHIN BOUNDS</p>";
        // }
        // else
        // {
        //     $returnMessage = "<p style='color:red'>OUT OF BOUNDS</p>";
        // }
    
        // if($inBBox) { $returnMessage .= ".....ALSO WITHIN BBOX.....";}
    
        // return $returnMessage . "<br>\n" . ".........Intersection Count: " . $intersectionCount;

        if ($inBounds)
        {
            return 1;
        }
        else
        {
            return 0;
        }
}

?>