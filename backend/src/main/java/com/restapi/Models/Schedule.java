package com.restapi.Models;

import java.sql.Date;
import java.sql.Time;
import java.util.HashSet;
import java.util.Set;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.JoinTable;
import jakarta.persistence.ManyToMany;

@Entity
public class Schedule {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private long id;

    @Column
    private String name;

    @Column(columnDefinition = "DATE")
    private Date date;

    @Column(columnDefinition = "TIME")
    private Time start;

    @Column(columnDefinition = "TIME")
    private Time end;

    @ManyToMany
    @JoinTable(name = "present", 
    joinColumns = { 
        @JoinColumn(name = "schedule_id") },
    inverseJoinColumns = { 
        @JoinColumn(name = "user_id") })
    private Set<User> users = new HashSet<>();

    public long getId() {
        return id;
    }

    public void setId(long id) {
        this.id = id;
    }

    public Set<User> getUsers() {
        return users;
    }

    public void setUsers(Set<User> users) {
        this.users = users;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public Date getDate() {
        return date;
    }

    public void setDate(Date date) {
        this.date = date;
    }

    public Time getStart() {
        return start;
    }

    public void setStart(Time start) {
        this.start = start;
    }

    public Time getEnd() {
        return end;
    }

    public void setEnd(Time end) {
        this.end = end;
    }

    public void addUser(User user) {
        this.users.add(user);
    }
}
