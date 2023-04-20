package com.restapi.Repo;

import java.sql.Date;
import java.sql.Time;
import java.util.List;

import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.CrudRepository;

import com.restapi.Models.Schedule;

import jakarta.websocket.server.PathParam;


public interface ScheduleRepo extends CrudRepository<Schedule, Long> {
 

    @Query("SELECT s FROM Schedule s WHERE date = :date AND start BETWEEN :start AND :end OR date = :date AND end BETWEEN :start AND :end OR date = :date AND start >= :start AND end >= :end")
    public List<Schedule> findCrash(@PathParam("date") Date date, @PathParam("start") Time start, @PathParam("end") Time end);

    @Query("SELECT s FROM Schedule s WHERE date = CURDATE() AND NOW() BETWEEN start AND end")
    public List<Schedule> findActiveSchedule();

}
