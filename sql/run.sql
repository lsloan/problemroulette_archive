use prexpansion;

/* usage by student */
select 
    user.username,
    class.name,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) as correct,
    count(*) as tried,
    count(distinct dayofyear(responses.start_time)) as days,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) / count(*) as rate,
    round(sum(to_seconds(responses.end_time) - to_seconds(responses.start_time))/count(*), 0) as avg_time,
    round(sum(to_seconds(responses.end_time) - to_seconds(responses.start_time)), 0) as tot_time
from responses
inner join `user`
    on user.id=responses.user_id 
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    /*
    class.name like 'Physics%'
    and dayofyear(responses.start_time) > dayofyear('03-09-13')
    and dayofyear(responses.start_time) <= dayofyear('03-10-13')
    class.name ='Chemistry 130'
    and dayofyear(responses.start_time) > dayofyear('03-09-13')
    and dayofyear(responses.start_time) <= dayofyear('03-10-16')
    class.name ='Chemistry 130'
    and dayofyear(responses.start_time) > dayofyear('16-10-13')
    and dayofyear(responses.start_time) <= dayofyear('22-11-13')
    class.name ='Chemistry 130'
    and dayofyear(responses.start_time) > dayofyear('03-10-13')
    and dayofyear(responses.start_time) <= dayofyear('22-11-13')
    */
    class.name ='MCDB 310'
group by concat(responses.user_id, class.name)
order by days, tried
;


