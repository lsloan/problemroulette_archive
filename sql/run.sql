/*use prexpansion;*/
use problemroulette;

/* chemistry usage by day */
select
    DATE(start_time) date,
    count(*)
from responses 
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    class.name='Chemistry 130'
group by DAYOFYEAR(start_time)
order by DAYOFYEAR(start_time)
;


exit


