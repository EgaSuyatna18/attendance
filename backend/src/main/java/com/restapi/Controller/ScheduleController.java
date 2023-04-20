package com.restapi.Controller;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Optional;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.restapi.Models.Schedule;
import com.restapi.Models.User;
import com.restapi.Repo.ScheduleRepo;
import com.restapi.Repo.UserRepo;

@RestController
@RequestMapping("/schedule")
public class ScheduleController {
    
    @Autowired
    private ScheduleRepo scheduleRepo;

    @Autowired
    private UserRepo userRepo;

    @GetMapping
    public ResponseEntity<Map<String, Object>> findAll() {
        Map<String, Object> data = new HashMap<>();
        data.put("data", scheduleRepo.findAll());
        data.put("message", "Got schedule succesfully");
        return ResponseEntity.ok().body(data);        
    }

    @PostMapping
    public ResponseEntity<Map<String, Object>> store(@RequestBody Schedule schedule) {
        List<Schedule> scheduleCrash = scheduleRepo.findCrash(schedule.getDate(), schedule.getStart(), schedule.getEnd());
        Map<String, Object> data = new HashMap<>();
        if(scheduleCrash.size() != 0) {
            data.put("message", "Schedule crash!");
            return ResponseEntity.status(425).body(data);
        }
        data.put("data", scheduleRepo.save(schedule));
        data.put("message", "Created schedule succesfully");
        return ResponseEntity.ok().body(data);
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<Map<String, Object>> destroy(@PathVariable("id") Long id) {
        scheduleRepo.deleteById(id);
        Map<String, Object> data = new HashMap<>();
        data.put("message", "Deleted schedule succesfully");
        return ResponseEntity.ok().body(data);
    }

    @PostMapping("/{scheduleId}/{userId}")
    public ResponseEntity<Map<String, Object>> addUser(@PathVariable("scheduleId") Long scheduleId, @PathVariable("userId") Long userId) {
        Optional<Schedule> schedule = scheduleRepo.findById(scheduleId);
        Optional<User> user = userRepo.findById(userId);
        Map<String, Object> data = new HashMap<>();
        if(!schedule.isPresent() || !user.isPresent()) {
            data.put("message", "Failed Add User!");
            return ResponseEntity.badRequest().body(data);
        }
        schedule.get().addUser(user.get());
        scheduleRepo.save(schedule.get());
        data.put("message", "Success add user.");
        return ResponseEntity.ok().body(data);
    }

    @GetMapping("/schedule")
    public Schedule schedule() {
        return scheduleRepo.findActiveSchedule().get(0);
    }

    @GetMapping("/{id}")
    public Schedule getOne(@PathVariable Long id) {
        return scheduleRepo.findById(id).get();
    }

    // @PutMapping("/{id}")
    // public ResponseEntity<Map<String, Object>> update(@PathVariable("id") Long id, @RequestBody schedule schedule) {
    //     List<schedule> scheduleCrash = scheduleRepo.findCrash(schedule.getDate(), schedule.getStart(), schedule.getEnd());
    //     Map<String, Object> data = new HashMap<>();
    //     if(scheduleCrash.size() != 0) {
    //         data.put("message", "Schedule crash!");
    //         return ResponseEntity.status(425).body(data);
    //     }
    //     Optional<schedule> scheduleGet = scheduleRepo.findById(id);
    //     if(!scheduleGet.isPresent()) {
    //         return ResponseEntity.internalServerError().body(null);
    //     }
    //     schedule scheduleUpdate = scheduleGet.get();
    //     scheduleUpdate.setName(schedule.getName());
    //     scheduleUpdate.setDate(schedule.getDate());
    //     scheduleUpdate.setStart(schedule.getStart());
    //     scheduleUpdate.setEnd(schedule.getEnd());
    //     scheduleRepo.save(scheduleUpdate);
    //     data.put("data", scheduleRepo.save(schedule));
    //     data.put("message", "Updated schedule succesfully");
    //     return ResponseEntity.ok().body(data);
    // }
}
